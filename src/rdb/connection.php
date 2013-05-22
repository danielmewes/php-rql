<?php namespace r;

require_once('protocolbuf/message/pb_message.php');
require_once('protocolbuf/parser/pb_parser.php');
require_once('pb_proto_ql2.php');
require_once("util.php");
require_once("datum.php");

class Connection
{
    public function __construct($host, $port = 28015, $db = null) {
        if (!isset($host)) throw new RqlDriverError("No host given.");
        if (!isset($port)) throw new RqlDriverError("No port given.");
        if (isset($db) && !is_string($db)) throw new RqlDriverError("Database must be a string.");
        
        $this->host = $host;
        $this->port = $port;
        
        if (isset($db))
            $this->useDb($db);
            
        $this->connect();
    }
    
    public function __destruct() {
        if ($this->isOpen())
            $this->close();
    }
    
    public function close() {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");
        
        fclose($this->socket);
        $this->socket = null;
        $this->activeTokens = null;
    }
    
    public function reconnect() {
        $this->close();
        $this->connect();
    }
    
    public function isOpen() {
        return isset($this->socket);
    }
    
    public function useDb($dbName) {
        $this->defaultDb = new Db($dbName);
    }
    
    public function _run(Query $query, $options) {
        if (isset($options) && !is_array($options)) throw new RqlDriverError("Options must be an array.");
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");
        
        // Generate a token for the request
        $tries = 0;
        $maxToken = 1 << 30;
        do {
            $token = rand(0, $maxToken);
            $haveCollision = isset($this->activeTokens[$token]);
        } while ($haveCollision && $tries++ < 1024);
        if ($haveCollision) {
            throw new RqlDriverError("Unable to generate a unique token for the query.");
        }
        
        // Send the request
        $pbTerm = $query->_getPBTerm();
        $pbQuery = $this->makeQuery();
        $pbQuery->set_token($token);
        $pbQuery->set_type(pb\Query_QueryType::PB_START);
        $pbQuery->set_query($pbTerm);
        $optsI = 0;
        if (isset($this->defaultDb)) {
            $pair = new pb\Query_AssocPair();
            $pair->set_key('db');
            $pair->set_val($this->defaultDb->_getPBTerm());
            $pbQuery->set_global_optargs($optsI++, $pair);
        }
        if (isset($options)) {
            foreach ($options as $key => $value) {
                $pair = new pb\Query_AssocPair();
                $pair->set_key($key);
                $pair->set_val(nativeToDatum($value)->_getPBTerm());
                $pbQuery->set_global_optargs($optsI++, $pair);
            }
        }
        $this->sendProtobuf($pbQuery);
        
        if (isset($options) && isset($options['noreply']) && $options['noreply'] === true) {
            return null;
        }
        else {
            // Await the response
            $response = $this->receiveResponse($token, $query);
            
            if ($response->type() == pb\Response_ResponseType::PB_SUCCESS_PARTIAL) {
                $this->activeTokens[$token] = true;
            }
            
            if ($response->type() == pb\Response_ResponseType::PB_SUCCESS_ATOM)
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
        $pbQuery->set_token($token);
        $pbQuery->set_type(pb\Query_QueryType::PB_CONTINUE);
        $this->sendProtobuf($pbQuery);
        
        // Await the response
        $response = $this->receiveResponse($token);
        
        if ($response->type() != pb\Response_ResponseType::PB_SUCCESS_PARTIAL) {
            unset($this->activeTokens[$token]);
        }
        
        return $response;
    }
    
    public function _stopQuery($token) {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected.");    
        if (!is_numeric($token)) throw new RqlDriverError("Token must be a number.");
        
        // Send the request
        $pbQuery = $this->makeQuery();
        $pbQuery->set_token($token);
        $pbQuery->set_type(pb\Query_QueryType::PB_STOP);
        $this->sendProtobuf($pbQuery);
        
        // Await the response (but don't check for errors. the stop response doesn't even have a type)
        $response = $this->receiveResponse($token, null, true);
        
        unset($this->activeTokens[$token]);
        
        return $response;
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
        if (is_null($response->type())) throw new RqlDriverError("Response message has no type.");
        
        if ($response->token() != $token) {
            throw new RqlDriverError("Received wrong token. Response does not match the request. Expected $token, received " . $response->token());
        }
         
        if ($response->type() == pb\Response_ResponseType::PB_CLIENT_ERROR) {
            throw new RqlDriverError("Server says PHP-RQL is buggy: " . $response->response(0)->r_str());
        }
        else if ($response->type() == pb\Response_ResponseType::PB_COMPILE_ERROR) {
            $backtrace = null;
            if (!is_null($response->backtrace()))
                $backtrace =  Backtrace::_fromProtobuffer($response->backtrace());
            throw new RqlUserError("Compile error: " . $response->response(0)->r_str(), $query, $backtrace);
        }
        else if ($response->type() == pb\Response_ResponseType::PB_RUNTIME_ERROR) {
            $backtrace = null;
            if (!is_null($response->backtrace()))
                $backtrace =  Backtrace::_fromProtobuffer($response->backtrace());
            throw new RqlUserError("Runtime error: " . $response->response(0)->r_str(), $query, $backtrace);
        }
    }
    
    private function createCursorFromResponse(pb\Response $response) {
        return new Cursor($this, $response);
    }
    
    private function createDatumFromResponse(pb\Response $response) {
        $datum = $response->response(0);
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
        $responseSize = stream_get_contents($this->socket, 4);
        if ($responseSize === false) throw new RqlDriverError("Unable to read from socket.");
        $responseSize = unpack("V", $responseSize);
        $responseSize = $responseSize[1];
        $responseBuf = stream_get_contents($this->socket, $responseSize);
        if ($responseBuf === false) throw new RqlDriverError("Unable to read from socket.");
        return $responseBuf;
    }
    
    private function connect() {
        if ($this->isOpen()) throw new RqlDriverError("Already connected");
    
        $this->socket = stream_socket_client("tcp://" . $this->host . ":" . $this->port, $errno, $errstr);
        if ($errno != 0 || $this->socket === false) {
            $this->socket = null;
            throw new RqlDriverError("Unable to connect: " . $errstr);
        }
        
        $this->sendVersion();
    }
    
    private function sendVersion() {
        if (!$this->isOpen()) throw new RqlDriverError("Not connected");
    
        $binaryVersion = pack("V", pb\VersionDummy_Version::PB_V0_1); // "V" is little endian, 32 bit unsigned integer
        $this->sendStr($binaryVersion);
    }
    
    private function sendStr($s) {
        $bytesWritten = 0;
        while ($bytesWritten < strlen($s)) {
            $result = fwrite($this->socket, substr($s, $bytesWritten));
            if ($result === false) throw new RqlDriverError("Unable to write to socket");
            $bytesWritten += $result;
        }
    }
    
    
    private $socket;
    private $host;
    private $port;
    private $defaultDb;
    private $activeTokens;
}

?>
