<?php namespace r;

require_once("util.php");
require_once("datum.php");

class Connection
{
    public function __construct($host, $port = 28015, $db = null, $apiKey = null, $timeout = null) {
        if (!isset($host)) throw new RqlDriverError("No host given.");
        if (!isset($port)) throw new RqlDriverError("No port given.");
        if (isset($apiKey) && !is_string($apiKey)) throw new RqlDriverError("The API key must be a string.");

        $this->host = $host;
        $this->port = $port;
        if (!isset($apiKey))
            $apiKey = "";
        $this->apiKey = $apiKey;
        $this->timeout = null;

        if (isset($db))
            $this->useDb($db);
        if (isset($timeout))
            $this->setTimeout($timeout);

        $this->connect();
    }

    public function __destruct() {
        if ($this->isOpen())
            $this->close(false);
    }

    public function close($noreplyWait = true) {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");
        
        if ($noreplyWait) {
            $this->noreplyWait();
        }

        fclose($this->socket);
        $this->socket = null;
        $this->activeTokens = null;
    }

    public function reconnect($noreplyWait = true) {
        if ($this->isOpen())
            $this->close($noreplyWait);
        $this->connect();
    }

    public function isOpen() {
        return isset($this->socket);
    }

    public function useDb($dbName) {
        if (!is_string($dbName)) throw new RqlDriverError("Database must be a string.");
        $this->defaultDb = new Db($dbName);
    }

    public function setTimeout($timeout) {
        if (!is_numeric($timeout)) throw new RqlDriverError("Timeout must be a number.");
        $this->applyTimeout($timeout);
        $this->timeout = $timeout;
    }
    
    public function noreplyWait() {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");

        // Generate a token for the request
        $token = $this->generateToken();

        // Send the request
        $pbQuery = $this->makeQuery();
        $pbQuery->setToken($token);
        $pbQuery->setType(pb\Query_QueryType::PB_NOREPLY_WAIT);
        $this->sendProtobuf($pbQuery);

        // Await the response
        $response = $this->receiveResponse($token);

        if ($response->getType() != pb\Response_ResponseType::PB_WAIT_COMPLETE) {
            throw new RqlDriverError("Unexpected response type to noreplyWait query.");
        }
    }

    public function _run(Query $query, $options, &$profile) {
        if (isset($options) && !is_array($options)) throw new RqlDriverError("Options must be an array.");
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");

        // Generate a token for the request
        $token = $this->generateToken();

        // Send the request
        $pbTerm = $query->_getPBTerm();
        $pbQuery = $this->makeQuery();
        $pbQuery->setToken($token);
        $pbQuery->setType(pb\Query_QueryType::PB_START);
        $pbQuery->setQuery($pbTerm);
        if (isset($this->defaultDb)) {
            $pair = new pb\Query_AssocPair();
            $pair->setKey('db');
            $pair->setVal($this->defaultDb->_getPBTerm());
            $pbQuery->appendGlobalOptargs($pair);
        }
        // This noJsonResponse option is just there for testing purposes and as a fallback
        // should there be any problems with our implementation of JSON responses
        if (isset($options['noJsonResponse']) && $options['noJsonResponse'] === true) {
            $pbQuery->setAcceptsRJson(false);
        } else {
            $pbQuery->setAcceptsRJson(true);
        }
        if (isset($options)) {
            foreach ($options as $key => $value) {
                $pair = new pb\Query_AssocPair();
                $pair->setKey($key);
                $pair->setVal(nativeToDatum($value)->_getPBTerm());
                $pbQuery->appendGlobalOptargs($pair);
            }
        }
        $this->sendProtobuf($pbQuery);

        if (isset($options) && isset($options['noreply']) && $options['noreply'] === true) {
            return null;
        }
        else {
            // Await the response
            $response = $this->receiveResponse($token, $query);

            if ($response->getType() == pb\Response_ResponseType::PB_SUCCESS_PARTIAL) {
                $this->activeTokens[$token] = true;
            }

            if ($response->getProfile() !== null) {
                $profile = protobufToDatum($response->getProfile());
            }

            if ($response->getType() == pb\Response_ResponseType::PB_SUCCESS_ATOM)
                return $this->createDatumFromResponse($response);
            else
                return $this->createCursorFromResponse($response);
        }
    }

    public function _continueQuery($token) {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");
        if (!is_numeric($token)) throw new RqlDriverError("Token must be a number.");

        // Send the request
        $pbQuery = $this->makeQuery();
        $pbQuery->setToken($token);
        $pbQuery->setType(pb\Query_QueryType::PB_CONTINUE);
        $this->sendProtobuf($pbQuery);

        // Await the response
        $response = $this->receiveResponse($token);

        if ($response->getType() != pb\Response_ResponseType::PB_SUCCESS_PARTIAL) {
            unset($this->activeTokens[$token]);
        }

        return $response;
    }

    public function _stopQuery($token) {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");
        if (!is_numeric($token)) throw new RqlDriverError("Token must be a number.");

        // Send the request
        $pbQuery = $this->makeQuery();
        $pbQuery->setToken($token);
        $pbQuery->setType(pb\Query_QueryType::PB_STOP);
        $this->sendProtobuf($pbQuery);

        // Await the response (but don't check for errors. the stop response doesn't even have a type)
        $response = $this->receiveResponse($token, null, true);

        unset($this->activeTokens[$token]);

        return $response;
    }
    
    private function generateToken() {
        $tries = 0;
        $maxToken = 1 << 30;
        do {
            $token = \rand(0, $maxToken);
            $haveCollision = isset($this->activeTokens[$token]);
        } while ($haveCollision && $tries++ < 1024);
        if ($haveCollision) {
            throw new RqlDriverError("Unable to generate a unique token for the query.");
        }
        return $token;
    }

    private function receiveResponse($token, $query = null, $noChecks = false) {
        $responseBuf = $this->receiveProtobuf();
        $response = new pb\Response();
        $response->ParseFromString($responseBuf);
        if (!$noChecks)
            $this->checkResponse($response, $token, $query);

        return $response;
    }

    private function checkResponse(pb\Response $response, $token, $query = null) {
        if (is_null($response->getType())) throw new RqlDriverError("Response message has no type.");

        if ($response->getType() == pb\Response_ResponseType::PB_CLIENT_ERROR) {
            throw new RqlDriverError("Server says PHP-RQL is buggy: " . $response->getResponseAt(0)->getRStr());
        }

        if ($response->getToken() != $token) {
            throw new RqlDriverError("Received wrong token. Response does not match the request. Expected $token, received " . $response->getToken());
        }

        if ($response->getType() == pb\Response_ResponseType::PB_COMPILE_ERROR) {
            $backtrace = null;
            if (!is_null($response->getBacktrace()))
                $backtrace =  Backtrace::_fromProtobuffer($response->getBacktrace());
            throw new RqlUserError("Compile error: " . $response->getResponseAt(0)->getRStr(), $query, $backtrace);
        }
        else if ($response->getType() == pb\Response_ResponseType::PB_RUNTIME_ERROR) {
            $backtrace = null;
            if (!is_null($response->getBacktrace()))
                $backtrace =  Backtrace::_fromProtobuffer($response->getBacktrace());
            throw new RqlUserError("Runtime error: " . $response->getResponseAt(0)->getRStr(), $query, $backtrace);
        }
    }

    private function createCursorFromResponse(pb\Response $response) {
        return new Cursor($this, $response);
    }

    private function createDatumFromResponse(pb\Response $response) {
        $datum = $response->getResponseAt(0);
        return protobufToDatum($datum);
    }

    private function makeQuery() {
        $query = new pb\Query();

        return $query;
    }

    private function sendProtobuf($protobuf) {
        $request = $protobuf->SerializeToString();
        $requestSize = pack("V", strlen($request));
        $this->sendStr($requestSize . $request);
    }

    private function receiveProtobuf() {
        $responseSize = $this->receiveStr(4);
        $responseSize = unpack("V", $responseSize);
        $responseSize = $responseSize[1];
        $responseBuf = $this->receiveStr($responseSize);
        return $responseBuf;
    }

    private function applyTimeout($timeout) {
        if ($this->isOpen()) {
            if (!stream_set_timeout($this->socket, $timeout)) {
                throw new RqlDriverError("Could not set timeout");
            }
        }
    }

    private function connect() {
        if ($this->isOpen()) throw new RqlDriverError("Already connected");

        $this->socket = stream_socket_client("tcp://" . $this->host . ":" . $this->port, $errno, $errstr);
        if ($errno != 0 || $this->socket === false) {
            $this->socket = null;
            throw new RqlDriverError("Unable to connect: " . $errstr);
        }
        if ($this->timeout) {
            $this->applyTimeout($this->timeout);
        }

        $this->sendHandshake();
        $this->receiveHandshakeResponse();
    }

    private function sendHandshake() {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected");

        $binaryVersion = pack("V", pb\VersionDummy_Version::PB_V0_2); // "V" is little endian, 32 bit unsigned integer
        $handshake = $binaryVersion;

        $binaryKeyLength = pack("V", strlen($this->apiKey));
        $handshake .= $binaryKeyLength . $this->apiKey;

        $this->sendStr($handshake);
    }

    private function receiveHandshakeResponse() {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected");

        $response = "";
        while (true) {
            $ch = stream_get_contents($this->socket, 1);
            if ($ch === false || strlen($ch) < 1) {
                $this->close(false);
                throw new RqlDriverError("Unable to read from socket during handshake. Disconnected.");
            }
            if ($ch === chr(0))
                break;
            else
                $response .= $ch;
        }

        if ($response != "SUCCESS") {
            $this->close(false);
            throw new RqlDriverError("Handshake failed: $response Disconnected.");
        }
    }

    private function sendStr($s) {
        $bytesWritten = 0;
        while ($bytesWritten < strlen($s)) {
            $result = fwrite($this->socket, substr($s, $bytesWritten));
            if ($result === false || $result === 0) {
                $metaData = stream_get_meta_data($this->socket);
                $this->close(false);
                if ($metaData['timed_out']) {
                    throw new RqlDriverError("Timed out while writing to socket. Disconnected. Call setTimeout(seconds) on the connection to change the timeout.");
                }
                throw new RqlDriverError("Unable to write to socket. Disconnected.");
            }
            $bytesWritten += $result;
        }
    }

    private function receiveStr($length) {
        $s = stream_get_contents($this->socket, $length);
        if ($s === false || strlen($s) < $length) {
            $metaData = stream_get_meta_data($this->socket);
            $this->close(false);
            if ($metaData['timed_out']) {
                throw new RqlDriverError("Timed out while reading from socket. Disconnected. Call setTimeout(seconds) on the connection to change the timeout.");
            }
            throw new RqlDriverError("Unable to read from socket. Disconnected.");
        }
        return $s;
    }


    private $socket;
    private $host;
    private $port;
    private $defaultDb;
    private $apiKey;
    private $activeTokens;
    private $timeout;
}

?>
