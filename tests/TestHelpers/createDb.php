<?php

include __DIR__ . '../../../vendor/autoload.php';

$conn = r\connect(getenv('RDB_HOST'), getenv('RDB_PORT'));
$db = getenv('RDB_DB');
$res = r\dbCreate($db)->run($conn);

if ($res['dbs_created'] !== 1.0) {
    echo 'Error creating DB' . PHP_EOL;
    exit;
}

r\db($db)->tableCreate('marvel', array('primary_key' => 'superhero'))->run($conn);
r\db($db)->tableCreate('dc_universe', array('primary_key' => 'name'))->run($conn);
r\db($db)->tableCreate('t5000', array('durability' => 'soft'))->run($conn);

$tables = array(
    't1',
    't2',
    'geo'
);

foreach ($tables as $table) {
    r\db($db)->tableCreate($table)->run($conn);
}

r\db($db)->table('t1')->indexCreate('other')->run($conn);
r\db($db)->table('t2')->indexCreate('other')->run($conn);

$geoTable = r\db($db)->table('geo');
$geoTable->indexCreateGeo('geo')->run($conn);
$geoTable->indexCreateMultiGeo('mgeo', function ($x) {
    return r\expr(array($x('geo')));

})->run($conn);
$geoTable->indexWait('geo')->run($conn);
$geoTable->indexWait('mgeo')->run($conn);
