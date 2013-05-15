<?php
require_once('rdb/rdb.php');

abstract class TestCase
{
    public abstract function run();

    protected $conn;
    protected $datasets;
    
    public function __construct(r\Connection $connection, &$datasets)
    {
        $this->conn = $connection;
        $this->datasets = &$datasets;
    }
    
    protected function requireDataset($name)
    {
        if (!isset($this->datasets[$name])) {
            $this->datasets[$name] = new $name($this->conn);
        }
    }
    
    protected function checkQueryResult($query, $expectedResult)
    {
        $result = $query->run($this->conn)->toNative();
        if ($expectedResult !== $result) // TODO: Test this
        {
            echo "Query result does not match.\n";
        }
    }
}

?>
