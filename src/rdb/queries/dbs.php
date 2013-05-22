<?php namespace r;

class Db extends Query
{
    public function table($tableName, $useOutdated = null) {
        return new Table($this, $tableName, $useOutdated);
    }
    public function tableCreate($tableName, $options = null) {
        return new TableCreate($this, $tableName, $options);
    }
    public function tableDrop($tableName) {
        return new TableDrop($this, $tableName);
    }
    public function tableList() {
        return new TableList($this);
    }


    public function __construct($dbName) {
        if (!\is_string($dbName)) throw new RqlDriverError("Database name must be a string.");
        $this->dbName = $dbName;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_DB);
        $subDatum = new StringDatum($this->dbName);
        $term->set_args(0, $subDatum->_getPBTerm());
        return $term;
    }
    
    private $dbName;
}

class DbCreate extends ValuedQuery
{
    public function __construct($dbName) {
        if (!\is_string($dbName)) throw new RqlDriverError("Database name must be a string.");
        $this->dbName = $dbName;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_DB_CREATE);
        $subDatum = new StringDatum($this->dbName);
        $term->set_args(0, $subDatum->_getPBTerm());
        return $term;
    }
    
    private $dbName;
}

class DbDrop extends ValuedQuery
{
    public function __construct($dbName) {
        if (!\is_string($dbName)) throw new RqlDriverError("Database name must be a string.");
        $this->dbName = $dbName;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_DB_DROP);
        $subDatum = new StringDatum($this->dbName);
        $term->set_args(0, $subDatum->_getPBTerm());
        return $term;
    }
    
    private $dbName;
}

class DbList extends ValuedQuery
{
    public function __construct() {
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_DB_LIST);
        return $term;
    }
}

?>
