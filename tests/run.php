<?php

$phpRqlIncludePath = "../src";
$serverHost = 'localhost';
$serverPort = 28015;


error_reporting(-1);
set_exception_handler(function ($e) { echo "Exception: " . $e->getMessage() . "\n\n"; debug_print_backtrace();  } );
set_include_path($phpRqlIncludePath);


require_once('rdb/rdb.php');
require_once('./Dataset.php');
require_once('./TestCase.php');


// Include all data sets
$datasetTypes = scandir('./Datasets');
foreach ($datasetTypes as $datasetType) {
    if ($datasetType[0] == ".") continue;
    require_once('./Datasets/' . $datasetType);
}


// Establish a connection to the server
$conn = r\connect($serverHost, $serverPort);


// Include one test case at a time and run it
$testCaseTypes = scandir('./TestCases');
$currentDatasets = array();
foreach ($testCaseTypes as $testCaseType) {
    if ($testCaseType[0] == ".") continue;
    require_once('./TestCases/' . $testCaseType);
    $testCaseType = str_replace(".php", "", $testCaseType);
    $testCase = new $testCaseType($conn, $currentDatasets);
    $testCase->run();
}


// Clean up
foreach ($currentDatasets as &$dataset) {
    $dataset->__destruct();
}
unset($currentDatasets);
$conn->close();

?>
