<?php namespace r;

class IndexList extends ValuedQuery
{
    public function __construct(Table $table) {
        $this->setPositionalArg(0, $table);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INDEX_LIST;
    }
}

class IndexCreate extends ValuedQuery
{
    public function __construct(Table $table, $indexName, $keyFunction = null) {
        if (!\is_string($indexName)) throw new RqlDriverError("Index name must be a string.");
        if (isset($keyFunction)) {
            if (!(is_object($keyFunction) && is_subclass_of($keyFunction, "\\r\\Query"))) {
                $keyFunction = nativeToFunction($keyFunction);
            } else if (!(is_object($keyFunction) && is_subclass_of($keyFunction, "\\r\\FunctionQuery"))) {
                $keyFunction = new RFunction(array(new RVar('_')), $keyFunction);
            }
        }
        
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, new StringDatum($indexName));
        if (isset($keyFunction))
            $this->setPositionalArg(2, $keyFunction);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INDEX_CREATE;
    }
}

class IndexDrop extends ValuedQuery
{
    public function __construct(Table $table, $indexName) {
        if (!\is_string($indexName)) throw new RqlDriverError("Index name must be a string.");
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, new StringDatum($indexName));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INDEX_DROP;
    }
}

?>
