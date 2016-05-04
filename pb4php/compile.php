<?php

require_once(__DIR__ . '/parser/pb_parser.php');

$rdbProtocolParser = new \PBParser();
$rdbProtocolParser->parse(
    __DIR__ . '/ql2.proto',
    'r\ProtocolBuffer', // namespace
    __DIR__ . '/../rdb/ProtocolBuffer' //destination dir
);
