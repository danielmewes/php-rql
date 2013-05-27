<?php namespace r;

class Get extends ValuedQuery
{
    public function __construct(Table $table, $key) {
        if (!(is_object($key) && is_subclass_of($key, "\\r\\Query"))) {
            if (is_numeric($key))
                $key = new NumberDatum($key);
            else
                $key = new StringDatum($key);
        }
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GET;
    }
}

class GetAll extends ValuedQuery
{
    public function __construct(Table $table, $key, $index = null) {
        if (isset($index))
            $index = new StringDatum($index);
        if (!(is_object($key) && is_subclass_of($key, "\\r\\Query"))) {
            $key = nativeToDatum($key);
        }
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
        if (isset($index))
            $this->setOptionalArg('index', $index);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GET_ALL;
    }
}

class Between extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $leftBound, $rightBound, $index = null) {
        if (isset($index))
            $index = new StringDatum($index);
        if (!(is_object($leftBound) && is_subclass_of($leftBound, "\\r\\Query"))) $leftBound = nativeToDatum($leftBound);
        if (!(is_object($rightBound) && is_subclass_of($rightBound, "\\r\\Query"))) $rightBound = nativeToDatum($rightBound);
        
        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $leftBound);
        $this->setPositionalArg(2, $rightBound);
        if (isset($index))
            $this->setOptionalArg('index', $index);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_BETWEEN;
    }
}

class Filter extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $predicate) {
        if (!(is_object($predicate) && is_subclass_of($predicate, "\\r\\Query"))) {
            try {
                $predicate = nativeToDatum($predicate);
                if (!is_subclass_of($predicate, "\\r\\Datum")) {
                    // $predicate is not a simple datum. Wrap it into a function:                
                    $predicate = new RFunction(array(new RVar('_')), $predicate);
                }
            } catch (RqlDriverError $e) {
                $predicate = nativeToFunction($predicate);
            }
        } else if (!(is_object($predicate) && is_subclass_of($predicate, "\\r\\FunctionQuery"))) {
            $predicate = new RFunction(array(new RVar('_')), $predicate);
        }
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $predicate);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_FILTER;
    }
}

?>
