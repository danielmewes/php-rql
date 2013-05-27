<?php namespace r;

class Insert extends ValuedQuery
{
    public function __construct(Table $table, $document, $upsert = null) {
        if (isset($upsert) && !\is_bool($upsert)) throw new RqlDriverError("Upsert must be bool.");
        if (!(is_object($document) && is_subclass_of($document, "\\r\\Query")))
            $document = nativeToDatum($document);
        
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $document);
        if (isset($upsert)) {
            $this->setOptionalArg('upsert', new BoolDatum($upsert));
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INSERT;
    }
}

class Update extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $delta, $nonAtomic = null) {
        if (isset($nonAtomic ) && !\is_bool($nonAtomic )) throw new RqlDriverError("nonAtomic must be bool.");
        if (!(is_object($delta) && is_subclass_of($delta, "\\r\\Query"))) {
            try {
                $delta = nativeToDatum($delta);
                if (!is_subclass_of($delta, "\\r\\Datum")) {
                    // $delta is not a simple datum. Wrap it into a function:                
                    $delta = new RFunction(array(new RVar('_')), $delta);
                }
            } catch (RqlDriverError $e) {
                $delta = nativeToFunction($delta);
            }
        } else if (!(is_object($delta) && is_subclass_of($delta, "\\r\\FunctionQuery")) && !(is_object($delta) && is_subclass_of($delta, "\\r\\Datum"))) {
            $delta = new RFunction(array(new RVar('_')), $delta);
        }
        
        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $delta);
        if (isset($nonAtomic)) {
            $this->setOptionalArg('non_atomic', new BoolDatum($nonAtomic));
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_UPDATE;
    }
}

class Delete extends ValuedQuery
{
    public function __construct(ValuedQuery $selection) {
        $this->setPositionalArg(0, $selection);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DELETE;
    }
}

class Replace extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $delta, $nonAtomic = null) {
        if (isset($nonAtomic ) && !\is_bool($nonAtomic )) throw new RqlDriverError("nonAtomic must be bool.");
        if (!(is_object($delta) && is_subclass_of($delta, "\\r\\Query"))) {
            // If we can make it an object, we will wrap that object into a function.
            // Otherwise, we will try to make it a function.
            try {
                $delta = nativeToDatum($delta);
                $delta = new RFunction(array(new RVar('_')), $delta);
            } catch (RqlDriverError $e) {
                $delta = nativeToFunction($delta);
            }
        }
        
        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $delta);
        if (isset($nonAtomic)) {
            $this->setOptionalArg('non_atomic', new BoolDatum($nonAtomic));
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_REPLACE;
    }
}

?>
