<?php namespace r;

require_once('protocolbuf/message/pb_message.php');
require_once('protocolbuf/parser/pb_parser.php');

if (!@include_once('pb_proto_ql2.php')) {
    echo "pb_proto_ql2.php not found. Assuming that we must generate it...\n";
    $rdbProtocolParser = new \PBParser();
    $rdbProtocolParser->parse('rdb/ql2.proto', 'r\pb');
    require_once('pb_proto_ql2.php');
    echo "pb_proto_ql2.php has been generated.\n";
}

require_once("util.php");
require_once("global.php");
require_once("misc.php");
require_once("connection.php");
require_once("datum.php");
require_once("queries.php");
require_once("function.php");

?>
