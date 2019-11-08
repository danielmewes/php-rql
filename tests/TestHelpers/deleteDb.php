<?php

use function r\connect;
use function r\dbDrop;

include __DIR__.'../../../vendor/autoload.php';

$conn = connect(getenv('RDB_HOST'), getenv('RDB_PORT'));

$res = dbDrop(getenv('RDB_DB'))->run($conn);

