<?php
require_once('rdb/rdb.php');

abstract class Dataset
{
    protected abstract function create();
    protected abstract function delete();

    protected $conn;
    
    
    private $mustDelete;
    
    public function __construct(r\Connection $connection) {
        $this->conn = $connection;
        $this->create();
        $this->mustDelete = true;
    }
    
    public function __destruct()
    {
        if ($this->mustDelete) {
            $this->mustDelete = false;
            $this->delete();
        }
    }
    
    public function reset()
    {
        $this->delete();
        $this->create();
    }
}

?>
