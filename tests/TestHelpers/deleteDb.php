<?php

include __DIR__ . '../../../vendor/autoload.php';

$conn = r\connect(getenv('RDB_HOST'), getenv('RDB_PORT'));

$res = r\dbDrop(getenv('RDB_DB'))->run($conn);
