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
        $this->setPositionalArg(0, new StringDatum($dbName));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DB;
    }
}

class DbCreate extends ValuedQuery
{
    public function __construct($dbName) {
        if (!\is_string($dbName)) throw new RqlDriverError("Database name must be a string.");
        $this->setPositionalArg(0, new StringDatum($dbName));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DB_CREATE;
    }
}

class DbDrop extends ValuedQuery
{
    public function __construct($dbName) {
        if (!\is_string($dbName)) throw new RqlDriverError("Database name must be a string.");
        $this->setPositionalArg(0, new StringDatum($dbName));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DB_DROP;
    }
}

class DbList extends ValuedQuery
{
    protected function getTermType() {
        return pb\Term_TermType::PB_DB_LIST;
    }
}

?>
