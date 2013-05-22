<?php namespace r;

class IndexList extends ValuedQuery
{
    public function __construct(Table $table) {
        $this->table = $table;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_INDEX_LIST);
        $term->set_args(0, $this->table->_getPBTerm());
        return $term;
    }
    
    private $table;
}

class IndexCreate extends ValuedQuery
{
    public function __construct(Table $table, $indexName, $keyFunction = null) {
        if (!isset($keyFunction)) $keyFunction = row($indexName);
        if (!\is_string($indexName)) throw new RqlDriverError("Index name must be a string.");
        if (!(is_object($keyFunction) && is_subclass_of($keyFunction, "\\r\\Query"))) {
            $keyFunction = nativeToFunction($keyFunction);
        } else if (!(is_object($keyFunction) && is_subclass_of($keyFunction, "\\r\\FunctionQuery"))) {
            $keyFunction = new RFunction(array(new RVar('_')), $keyFunction);
        }
        
        $this->table = $table;
        $this->indexName = $indexName;
        $this->keyFunction = $keyFunction;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_INDEX_CREATE);
        $term->set_args(0, $this->table->_getPBTerm());
        $subDatum = new StringDatum($this->indexName);
        $term->set_args(1, $subDatum->_getPBTerm());
        $term->set_args(2, $this->keyFunction->_getPBTerm());
        return $term;
    }
    
    private $table;
    private $indexName;
    private $keyFunction;
}

class IndexDrop extends ValuedQuery
{
    public function __construct(Table $table, $indexName) {
        if (!\is_string($indexName)) throw new RqlDriverError("Index name must be a string.");
        $this->table = $table;
        $this->indexName = $indexName;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_INDEX_DROP);
        $term->set_args(0, $this->table->_getPBTerm());
        $subDatum = new StringDatum($this->indexName);
        $term->set_args(1, $subDatum->_getPBTerm());
        return $term;
    }
    
    private $table;
    private $indexName;
}

?>
