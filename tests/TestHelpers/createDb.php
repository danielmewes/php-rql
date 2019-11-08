<?php

use function r\connect;
use function r\db;
use function r\dbCreate;
use function r\expr;

include __DIR__.'../../../vendor/autoload.php';

$conn = connect(getenv('RDB_HOST'), getenv('RDB_PORT'));
$db = getenv('RDB_DB');
$res = dbCreate($db)->run($conn);

if (1.0 !== $res['dbs_created']) {
    echo 'Error creating DB'.PHP_EOL;
    exit;
}

db($db)->tableCreate('marvel', ['primary_key' => 'superhero'])->run($conn);
db($db)->tableCreate('dc_universe', ['primary_key' => 'name'])->run($conn);
db($db)->tableCreate('t5000', ['durability' => 'soft'])->run($conn);

$tables = [
    't1',
    't2',
    'geo',
];

foreach ($tables as $table) {
    db($db)->tableCreate($table)->run($conn);
}

db($db)->table('t1')->indexCreate('other')->run($conn);
db($db)->table('t2')->indexCreate('other')->run($conn);

$geoTable = db($db)->table('geo');
$geoTable->indexCreateGeo('geo')->run($conn);
$geoTable->indexCreateMultiGeo('mgeo', function ($x) {
    return expr([$x('geo')]);
})->run($conn);
$geoTable->indexWait('geo')->run($conn);
$geoTable->indexWait('mgeo')->run($conn);
