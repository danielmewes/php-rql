<?php

namespace r;

use r\ProtocolBuffer\VersionDummyVersion;
use r\Exceptions\RqlServerError;
use r\Exceptions\RqlDriverError;

class Handshake
{
    private $username;
    private $password;
    private $protocol_version = 0;
    private $state;
    private $myR;
    private $clientFirstMessage;
    private $serverSignature;

    public function __construct($username, $password)
    {
        $this->username = str_replace(",", "=2C", str_replace("=", "=3D", $username));
        $this->password = $password;
        $this->state = 0;
    }

    public function nextMessage($response)
    {
        if ($this->state == 0) {
            $response == null or die("Illegal handshake state");

            $this->myR = base64_encode(openssl_random_pseudo_bytes(18));
            $this->clientFirstMessage = "n=" . $this->username . ",r=" . $this->myR;

            $binaryVersion = pack("V", VersionDummyVersion::PB_V1_0); // "V" is little endian, 32 bit unsigned integer

            $this->state = 1;
            return
                $binaryVersion
                . json_encode(
                    array(
                        "protocol_version" => $this->protocol_version,
                        "authentication_method" => "SCRAM-SHA-256",
                        "authentication" => "n,," . $this->clientFirstMessage
                    )
                )
                . chr(0);
        } elseif ($this->state == 1) {
            if (strpos($response, "ERROR") === 0) {
                throw new RqlDriverError(
                    "Received an unexpected reply. You may be attempting to connect to "
                    . "a RethinkDB server that is too old for this driver. The minimum "
                    . "supported server version is 2.3.0."
                );
            }

            $json = json_decode($response, true);
            if ($json["success"] === false) {
                throw new RqlDriverError("Handshake failed: " . $json["error"]);
            }
            if ($this->protocol_version > $json["max_protocol_version"]
                || $this->protocol_version < $json["min_protocol_version"]) {
                throw new RqlDriverError("Unsupported protocol version.");
            }

            $this->state = 2;
            return "";
        } elseif ($this->state == 2) {
            $json = json_decode($response, true);
            if ($json["success"] === false) {
                throw new RqlDriverError("Handshake failed: " . $json["error"]);
            }
            $serverFirstMessage = $json["authentication"];
            $authentication = array();
            foreach (explode(",", $json["authentication"]) as $var) {
                $pair = explode("=", $var);
                $authentication[$pair[0]] = $pair[1];
            }
            $serverR = $authentication["r"];
            if (strpos($serverR, $this->myR) !== 0) {
                throw new RqlDriverError("Invalid nonce from server.");
            }
            $salt = base64_decode($authentication["s"]);
            $iterations = (int)$authentication["i"];

            $clientFinalMessageWithoutProof = "c=biws,r=" . $serverR;
            $saltedPassword = $this->pkbdf2Hmac($this->password, $salt, $iterations);
            $clientKey = hash_hmac("sha256", "Client Key", $saltedPassword, true);
            $storedKey = hash("sha256", $clientKey, true);

            $authMessage =
                $this->clientFirstMessage . "," . $serverFirstMessage . "," . $clientFinalMessageWithoutProof;

            $clientSignature = hash_hmac("sha256", $authMessage, $storedKey, true);

            $clientProof = $clientKey ^ $clientSignature;

            $serverKey = hash_hmac("sha256", "Server Key", $saltedPassword, true);

            $this->serverSignature = hash_hmac("sha256", $authMessage, $serverKey, true);

            $this->state = 3;
            return
                json_encode(
                    array(
                        "authentication" => $clientFinalMessageWithoutProof . ",p=" . base64_encode($clientProof)
                    )
                )
                . chr(0);
        } elseif ($this->state == 3) {
            $json = json_decode($response, true);
            if ($json["success"] === false) {
                throw new RqlDriverError("Handshake failed: " . $json["error"]);
            }
            $authentication = array();
            foreach (explode(",", $json["authentication"]) as $var) {
                $pair = explode("=", $var);
                $authentication[$pair[0]] = $pair[1];
            }

            $v = base64_decode($authentication["v"]);

            // TODO: Use cryptographic comparison
            if ($v != $this->serverSignature) {
                throw new RqlDriverError("Invalid server signature.");
            }

            $this->state = 4;
            return null;
        } else {
            die("Illegal handshake state");
        }
    }

    private function pkbdf2Hmac($password, $salt, $iterations)
    {
        $t = hash_hmac("sha256", $salt . "\x00\x00\x00\x01", $password, true);
        $u = $t;
        for ($i = 0; $i < $iterations - 1; ++$i) {
            $t = hash_hmac("sha256", $t, $password, true);
            $u = $u ^ $t;
        }
        return $u;
    }
}
