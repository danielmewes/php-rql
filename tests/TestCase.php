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
        if (is_a($a1, "ArrayObject")) $a1 = $a1->getArrayCopy();
        if (is_a($a2, "ArrayObject")) $a2 = $a2->getArrayCopy();
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
        if (is_a($result, "r\Cursor")) {
            $result = $result->toArray();
        }

        $equal = false;

        if ((is_array($result) || is_a($result, "ArrayObject")) && is_array($expectedResult))
            $equal = $this->compareArrays($result, $expectedResult);
        elseif (is_object($result) && is_object($expectedResult))
            $equal = $expectedResult == $result;
        else
            $equal = $expectedResult === $result;

        if (!$equal)
        {
            echo "Query result does not match.\n";
            echo "  Was: \n";
            print_r($result);
            echo "  Expected: \n";
            print_r($expectedResult);
            echo "  In query: " . $query . "\n";
        }
    }
}

?>
