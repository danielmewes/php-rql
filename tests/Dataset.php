<?php
require_once('rdb/rdb.php');

abstract class Dataset
{
    protected abstract function create();
    protected abstract function delete();

    protected $conn;
    
    public function __construct(r\Connection $connection) {
        $this->conn = $connection;
        $this->create();
    }
    
    public function __destruct()
    {
        $this->delete();
    }
    
    public function reset()
    {
        $this->delete();
        $this->create();
    }
}

?>
