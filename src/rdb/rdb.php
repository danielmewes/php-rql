<?php namespace r;

$__PHP_RQL_PROTOBUF_BACKEND = 'pb4php';
if (class_exists('\ProtobufMessage')) {
    // Use (faster) php-protobuf backend
    $__PHP_RQL_PROTOBUF_BACKEND = 'php-protobuf';
}

if ($__PHP_RQL_PROTOBUF_BACKEND == 'php-protobuf') {
    require_once('php-protobuf_pb_proto_ql2.php');
}
else if ($__PHP_RQL_PROTOBUF_BACKEND == 'pb4php') {
    require_once('pb4php/message/pb_message.php');
    require_once('pb4php_pb_proto_ql2.php');
}
else throw new Exception("Unknown PHP-RQL protobuf backend: " . $__PHP_RQL_PROTOBUF_BACKEND);


require_once("util.php");
require_once("global.php");
require_once("misc.php");
require_once("connection.php");
require_once("datum.php");
require_once("queries.php");
require_once("function.php");
require_once("version.php");


function systemInfo()
{
    global $__PHP_RQL_PROTOBUF_BACKEND;
    global $__PHP_RQL_VERSION;
    $result = "";
    $result .=  "Protobuf backend: " . $__PHP_RQL_PROTOBUF_BACKEND . "\n";
    $result .=  "PHP-RQL Version: " . $__PHP_RQL_VERSION . "\n";
    return $result;
}

?>
