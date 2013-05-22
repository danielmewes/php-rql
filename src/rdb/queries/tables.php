<?php namespace r;

class TableList extends ValuedQuery
{
    public function __construct($database) {
        if (isset($database) && !is_a($database, "\\r\\Db")) throw ("Database is not a Db object.");
        $this->database = $database;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_TABLE_LIST);
        if (isset($this->database))
            $term->set_args(0, $this->database->_getPBTerm());
        return $term;
    }
    
    private $database;
}

class TableCreate extends ValuedQuery
{
    public function __construct($database, $tableName, $options = null) {
        if (isset($database) && !is_a($database, "\\r\\Db")) throw ("Database is not a Db object.");
        if (!\is_string($tableName)) throw new RqlDriverError("Table name must be a string.");
        if (isset($options)) {
            if (!is_array($options)) throw new RqlDriverError("Options must be an array.");
            foreach ($options as $key => &$val) {
                if (!is_string($key)) throw new RqlDriverError("Option keys must be strings.");
                if (!(is_object($val) && is_subclass_of($val, "\\r\\Query"))) {
                    $val = nativeToDatum($val);
                }
            }
        }
        
        $this->database = $database;
        $this->tableName = $tableName;
        $this->options = $options;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_TABLE_CREATE);
        $i = 0;
        if (isset($this->database)) {
            $term->set_args($i++, $this->database->_getPBTerm());
        }
        $subDatum = new StringDatum($this->tableName);
        $term->set_args($i++, $subDatum->_getPBTerm());
        if (isset($this->options)) {
            $i = 0;
            foreach ($this->options as $key => $val) {
                $pair = new pb\Term_AssocPair();
                $pair->set_key($key);
                $pair->set_val($val->_getPBTerm());
                $term->set_optargs($i, $pair);
                ++$i;
            }
        }
        return $term;
    }
    
    private $database;
    private $tableName;
    private $options;
}

class TableDrop extends ValuedQuery
{
    public function __construct($database, $tableName) {
        if (isset($database) && !is_a($database, "\\r\\Db")) throw ("Database is not a Db object.");
        if (!\is_string($tableName)) throw new RqlDriverError("Table name must be a string.");
        $this->database = $database;
        $this->tableName = $tableName;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_TABLE_DROP);
        $i = 0;
        if (isset($this->database)) {
            $term->set_args($i++, $this->database->_getPBTerm());
        }
        $subDatum = new StringDatum($this->tableName);
        $term->set_args($i++, $subDatum->_getPBTerm());
        return $term;
    }
    
    private $database;
    private $tableName;
}

class Table extends ValuedQuery
{
    public function insert($document, $upsert = null) {
        return new Insert($this, $document, $upsert);
    }
    public function get($key) {
        return new Get($this, $key);
    }
    public function getAll($key, $index = null) {
        return new GetAll($this, $key, $index);
    }
    public function indexCreate($indexName, $keyFunction = null) {
        return new IndexCreate($this, $indexName, $keyFunction);
    }
    public function indexDrop($indexName) {
        return new IndexDrop($this, $indexName);
    }
    public function indexList() {
        return new IndexList($this);
    }
    

    public function __construct($database, $tableName, $useOutdated = null) {
        if (isset($database) && !is_a($database, "\\r\\Db")) throw ("Database is not a Db object.");
        if (!\is_string($tableName)) throw new RqlDriverError("Table name must be a string.");
        if (isset($useOutdated) && !is_bool($useOutdated)) throw new RqlDriverError("Use outdated must be bool.");
        $this->database = $database;
        $this->tableName = $tableName;
        $this->useOutdated = $useOutdated;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_TABLE);
        $i = 0;
        if (isset($this->database)) {
            $term->set_args($i++, $this->database->_getPBTerm());
        }
        $subDatum = new StringDatum($this->tableName);
        $term->set_args($i++, $subDatum->_getPBTerm());
        if (isset($this->useOutdated)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("use_outdated");
            $subTerm = new BoolDatum($this->useOutdated);
            $pair->set_val($subTerm->_getPBTerm());
            $term->set_optargs(0, $pair);
        }
        return $term;
    }
    
    private $database;
    private $tableName;
    private $useOutdated;
}

?>
