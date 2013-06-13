<?php

require_once('../pb4php/parser/pb_parser.php');

$rdbProtocolParser = new \PBParser();
$rdbProtocolParser->parse('../rdb/ql2.proto', 'r\pb'); 

?>
