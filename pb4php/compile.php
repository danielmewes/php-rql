<?php

require_once(__DIR__ . '/parser/pb_parser.php');

$rdbProtocolParser = new \PBParser();
$rdbProtocolParser->parse(
    'src/rdb/ql2.proto', // not used
    'r\ProtocolBuffer', // namespace
    __DIR__ . '/..//src/rdb/ProtocolBuffer' //destination dir
);
