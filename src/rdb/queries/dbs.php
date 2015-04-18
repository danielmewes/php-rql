<?php namespace r;

class Db extends Query
{
    public function table($tableName, $useOutdatedOrOpts = null) {
        return new Table($this, $tableName, $useOutdatedOrOpts);
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
    public function wait($opts = null) {
        return new Wait($this, $opts);
    }
    public function reconfigure($opts = null) {
        return new Reconfigure($this, $opts);
    }
    public function rebalance() {
        return new Rebalance($this);
    }


    public function __construct($dbName) {
        $dbName = nativeToDatum($dbName);
        $this->setPositionalArg(0, $dbName);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DB;
    }
}

class DbCreate extends ValuedQuery
{
    public function __construct($dbName) {
        $dbName = nativeToDatum($dbName);
        $this->setPositionalArg(0, $dbName);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DB_CREATE;
    }
}

class DbDrop extends ValuedQuery
{
    public function __construct($dbName) {
        $dbName = nativeToDatum($dbName);
        $this->setPositionalArg(0, $dbName);
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
