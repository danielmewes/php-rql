<?php namespace r;

require_once('pb4php_pb_proto_ql2.php');

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
    global $__PHP_RQL_VERSION;
    $result = "";
    $result .=  "PHP-RQL Version: " . $__PHP_RQL_VERSION . "\n";
    return $result;
}

?>
