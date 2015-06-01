<?php

$phpRqlIncludePath = "../src";
$serverHost = 'localhost';
$serverPort = 28015;
$serverKey = null;

// TODO: Implement proper command line argument parsing using getopt(), so we
//  can also specify the server address etc.
$testCaseSelection = null;
if (count($argv) > 1) {
    $testCaseSelection = array();
    for ($i = 1; $i < count($argv); ++$i)
        $testCaseSelection[] = $argv[$i];
}


error_reporting(-1);
set_exception_handler(function ($e) {
        echo "Exception: " . $e . "\n";
        global $currentDatasets;
        foreach ($currentDatasets as &$dataset) {
            $dataset->__destruct();
            unset ($dataset);
        }
    });
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
$conn = r\connect($serverHost, $serverPort, null, $serverKey);


// Include one test case at a time and run it
$testCaseTypes = scandir('./TestCases');
$currentDatasets = array();
foreach ($testCaseTypes as $testCaseType) {
    if ($testCaseType[0] == ".") continue;
    $testCaseType = str_replace(".php", "", $testCaseType);
    if ($testCaseSelection && !in_array($testCaseType, $testCaseSelection)) continue;
    require_once('./TestCases/' . $testCaseType . ".php");
    $testCase = new $testCaseType($conn, $currentDatasets);
    echo "Running " . $testCaseType . "...";
    $testCase->run();
    echo " done\n";
}


// Clean up
foreach ($currentDatasets as &$dataset) {
    $dataset->__destruct();
    unset ($dataset);
}
$conn->close();

?>
