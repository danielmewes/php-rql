<?php namespace r;

class TableList extends ValuedQuery
{
    public function __construct($database) {
        if (isset($database) && !is_a($database, "\\r\\Db")) throw ("Database is not a Db object.");
        if (isset($database))
            $this->setPositionalArg(0, $database);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TABLE_LIST;
    }
}

class TableCreate extends ValuedQuery
{
    public function __construct($database, $tableName, $options = null) {
        if (isset($database) && !is_a($database, "\\r\\Db")) throw ("Database is not a Db object.");
        $tableName = nativeToDatum($tableName);
        if (isset($options)) {
            if (!is_array($options)) throw new RqlDriverError("Options must be an array.");
            foreach ($options as $key => &$val) {
                if (!is_string($key)) throw new RqlDriverError("Option keys must be strings.");
                if (!(is_object($val) && is_subclass_of($val, "\\r\\Query"))) {
                    $val = nativeToDatum($val);
                }
                unset($val);
            }
        }
        
        $i = 0;
        if (isset($database))
            $this->setPositionalArg($i++, $database);
        $this->setPositionalArg($i++, $tableName);
        if (isset($options)) {
            foreach ($options as $key => $val) {
                $this->setOptionalArg($key, $val);
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TABLE_CREATE;
    }
}

class TableDrop extends ValuedQuery
{
    public function __construct($database, $tableName) {
        if (isset($database) && !is_a($database, "\\r\\Db")) throw ("Database is not a Db object.");
        $tableName = nativeToDatum($tableName);

        $i = 0;
        if (isset($database))
            $this->setPositionalArg($i++, $database);
        $this->setPositionalArg($i++, $tableName);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TABLE_DROP;
    }
}

class Table extends ValuedQuery
{
    public function insert($document, $opts = null) {
        return new Insert($this, $document, $opts);
    }
    public function get($key) {
        return new Get($this, $key);
    }
    public function getAll($key, $opts = null) {
        return new GetAll($this, $key, $opts);
    }
    public function getMultiple($keys, $opts = null) {
        return new GetMultiple($this, $keys, $opts);
    }
    public function sync() {
        return new Sync($this);
    }
    public function indexCreate($indexName, $keyFunction = null) {
        return new IndexCreate($this, $indexName, $keyFunction);
    }
    public function indexCreateMulti($indexName, $keyFunction = null) {
        return new IndexCreate($this, $indexName, $keyFunction, array('multi' => true));
    }
    public function indexDrop($indexName) {
        return new IndexDrop($this, $indexName);
    }
    public function indexList() {
        return new IndexList($this);
    }
    public function indexStatus($indexNames = null) {
        return new IndexStatus($this, $indexNames);
    }
    public function indexWait($indexNames = null) {
        return new IndexWait($this, $indexNames);
    }
    

    public function __construct($database, $tableName, $useOutdated = null) {
        if (isset($database) && !is_a($database, "\\r\\Db")) throw ("Database is not a Db object.");
        $tableName = nativeToDatum($tableName);
        if (isset($useOutdated) && !is_bool($useOutdated)) throw new RqlDriverError("Use outdated must be bool.");
        
        $i = 0;
        if (isset($database))
            $this->setPositionalArg($i++, $database);
        $this->setPositionalArg($i++, $tableName);
        if (isset($useOutdated)) {
            $this->setOptionalArg('use_outdated', new BoolDatum($useOutdated));
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TABLE;
    }
}

?>
