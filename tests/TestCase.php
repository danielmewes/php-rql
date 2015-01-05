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
    
    protected function compareArrays($a1, $a2)
    {
        if (!is_array($a1) && !is_array($a2)) return $a1 == $a2;
        else if (!(is_array($a1) && is_array($a2))) return false;
        if (count($a1) != count($a2)) return false;
        $equal = true;
        foreach ($a1 as $k => $left) {
            if (!$equal) break;
            $right = null;
            if (is_numeric($k)) {
                foreach ($a2 as $r) {
                    if ($this->compareArrays($left, $r)) {
                        $right = $r;
                        break;
                    }
                }
            } else {
                if (!array_key_exists($k, $a2)) return false;
                $right = $a2[$k];
            }
            $equal = $equal && $this->compareArrays($left, $right);
        }
        return $equal;
    }
    
    protected function checkQueryResult($query, $expectedResult, $runOptions = array())
    {
        $result = $query->run($this->conn, $runOptions);
        $nativeResult = $result->toNative();

        $equal = false;

        if (is_array($nativeResult) && is_array($expectedResult))
            $equal = $this->compareArrays($nativeResult, $expectedResult);
        else
            $equal = $expectedResult === $nativeResult;

        if (!$equal)
        {
            echo "Query result does not match.\n";
            echo "  Was: \n";
            print_r($nativeResult);
            echo "  Expected: \n";
            print_r($expectedResult);
            echo "  In query: " . $query . "\n";
        }
    }
}

?>
