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
        $jsonQuery = array(pb\Query_QueryType::PB_NOREPLY_WAIT);
        $this->sendQuery($token, $jsonQuery);

        // Await the response
        $response = $this->receiveResponse($token);

        if ($response['t'] != pb\Response_ResponseType::PB_WAIT_COMPLETE) {
            throw new RqlDriverError("Unexpected response type to noreplyWait query.");
        }
    }

    public function _run(Query $query, $options, &$profile) {
        if (isset($options) && !is_array($options)) throw new RqlDriverError("Options must be an array.");
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");

        // Generate a token for the request
        $token = $this->generateToken();

        // Send the request
        $jsonTerm = $query->_getJSONTerm();
        $globalOptargs = array();
        if (isset($this->defaultDb)) {
            $globalOptargs['db'] = $this->defaultDb->_getJSONTerm();
        }
        if (isset($options)) {
            foreach ($options as $key => $value) {
                $globalOptargs[$key] = nativeToDatum($value)->_getJSONTerm();
            }
        }
        $jsonQuery = array(pb\Query_QueryType::PB_START, $jsonTerm, (Object)$globalOptargs);
        $this->sendQuery($token, $jsonQuery);

        if (isset($options) && isset($options['noreply']) && $options['noreply'] === true) {
            return null;
        }
        else {
            // Await the response
            $response = $this->receiveResponse($token, $query);

            if ($response['t'] == pb\Response_ResponseType::PB_SUCCESS_PARTIAL
                || $response['t'] == pb\Response_ResponseType::PB_SUCCESS_ATOM_FEED
                || $response['t'] == pb\Response_ResponseType::PB_SUCCESS_FEED) {
                $this->activeTokens[$token] = true;
            }

            if (isset($response['p'])) {
                $profile = decodedJSONToDatum($response['p']);
            }

            if ($response['t'] == pb\Response_ResponseType::PB_SUCCESS_ATOM)
                return $this->createDatumFromResponse($response);
            else
                return $this->createCursorFromResponse($response, $token);
        }
    }

    public function _continueQuery($token) {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");
        if (!is_numeric($token)) throw new RqlDriverError("Token must be a number.");

        // Send the request
        $jsonQuery = array(pb\Query_QueryType::PB_CONTINUE);
        $this->sendQuery($token, $jsonQuery);

        // Await the response
        $response = $this->receiveResponse($token);

        if ($response['t'] != pb\Response_ResponseType::PB_SUCCESS_PARTIAL
            && $response['t'] != pb\Response_ResponseType::PB_SUCCESS_ATOM_FEED
            && $response['t'] != pb\Response_ResponseType::PB_SUCCESS_FEED) {
            unset($this->activeTokens[$token]);
        }

        return $response;
    }

    public function _stopQuery($token) {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");
        if (!is_numeric($token)) throw new RqlDriverError("Token must be a number.");

        // Send the request
        $jsonQuery = array(pb\Query_QueryType::PB_STOP);
        $this->sendQuery($token, $jsonQuery);

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
        $responseHeader = $this->receiveStr(4 + 8);
        $responseHeader = unpack("Vtoken/Vtoken2/Vsize", $responseHeader);
        $responseToken = $responseHeader['token'];
        if ($responseHeader['token2'] != 0) {
            throw new RqlDriverError("Invalid response from server: Invalid token.");
        }
        $responseSize = $responseHeader['size'];
        $responseBuf = $this->receiveStr($responseSize);

        $response = json_decode($responseBuf);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new RqlDriverError("Unable to decode JSON response (error code " . json_last_error() . ")");
        }
        if (!is_object($response)) {
            throw new RqlDriverError("Invalid response from server: Not an object.");
        }
        $response = (array)$response;
        if (!$noChecks)
            $this->checkResponse($response, $responseToken, $token, $query);

        return $response;
    }

    private function checkResponse($response, $responseToken, $token, $query = null) {
        if (!isset($response['t'])) throw new RqlDriverError("Response message has no type.");

        if ($response['t'] == pb\Response_ResponseType::PB_CLIENT_ERROR) {
            throw new RqlDriverError("Server says PHP-RQL is buggy: " . $response['r'][0]);
        }

        if ($responseToken != $token) {
            throw new RqlDriverError("Received wrong token. Response does not match the request. Expected $token, received " . $responseToken);
        }

        if ($response['t'] == pb\Response_ResponseType::PB_COMPILE_ERROR) {
            $backtrace = null;
            if (isset($response['b']))
                $backtrace = Backtrace::_fromJSON($response['b']);
            throw new RqlUserError("Compile error: " . $response['r'][0], $query, $backtrace);
        }
        else if ($response['t'] == pb\Response_ResponseType::PB_RUNTIME_ERROR) {
            $backtrace = null;
            if (isset($response['b']))
                $backtrace = Backtrace::_fromJSON($response['b']);
            throw new RqlUserError("Runtime error: " . $response['r'][0], $query, $backtrace);
        }
    }

    private function createCursorFromResponse($response, $token) {
        return new Cursor($this, $response, $token);
    }

    private function createDatumFromResponse($response) {
        $datum = $response['r'][0];
        return decodedJSONToDatum($datum);
    }

    private function sendQuery($token, $json) {
        // PHP by default loses some precision when encoding floats, so we temporarily
        // bump up the `precision` option to avoid this.
        // The 17 assumes IEEE-754 double precision numbers.
        // Source: http://docs.oracle.com/cd/E19957-01/806-3568/ncg_goldberg.html
        //         "The same argument applied to double precision shows that 17 decimal
        //          digits are required to recover a double precision number."
        $previousPrecision = ini_set("precision", 17);
        $request = json_encode($json);
        if ($previousPrecision !== false) {
            ini_set("precision", $previousPrecision);
        }
        if ($request === false) throw new RqlDriverError("Failed to encode query as JSON: " . json_last_error());
    
        $requestSize = pack("V", strlen($request));
        $binaryToken = pack("V", $token) . pack("V", 0);
        $this->sendStr($binaryToken . $requestSize . $request);
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

        $binaryVersion = pack("V", pb\VersionDummy_Version::PB_V0_3); // "V" is little endian, 32 bit unsigned integer
        $handshake = $binaryVersion;

        $binaryKeyLength = pack("V", strlen($this->apiKey));
        $handshake .= $binaryKeyLength . $this->apiKey;

        $binaryProtocol = pack("V", pb\VersionDummy_Protocol::PB_JSON);
        $handshake .= $binaryProtocol;

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
        $s = "";
        while (strlen($s) < $length) {
            $partialS = stream_get_contents($this->socket, $length - strlen($s));
            if ($partialS === false) {
                $metaData = stream_get_meta_data($this->socket);
                $this->close(false);
                if ($metaData['timed_out']) {
                    throw new RqlDriverError("Timed out while reading from socket. Disconnected. Call setTimeout(seconds) on the connection to change the timeout.");
                }
                throw new RqlDriverError("Unable to read from socket. Disconnected.");
            }
            $s = $s . $partialS;
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
