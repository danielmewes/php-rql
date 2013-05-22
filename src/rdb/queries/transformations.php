<?php namespace r;

class Map extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $mappingFunction) {
        if (!(is_object($mappingFunction) && is_subclass_of($mappingFunction, "\\r\\Query"))) {
            $mappingFunction = nativeToFunction($mappingFunction);
        } else if (!(is_object($mappingFunction) && is_subclass_of($mappingFunction, "\\r\\FunctionQuery"))) {
            $mappingFunction = new RFunction(array(new RVar('_')), $mappingFunction);
        }
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $mappingFunction);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_MAP;
    }
}

class ConcatMap extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $mappingFunction) {
        if (!(is_object($mappingFunction) && is_subclass_of($mappingFunction, "\\r\\Query"))) {
            $mappingFunction = nativeToFunction($mappingFunction);
        } else if (!(is_object($mappingFunction) && is_subclass_of($mappingFunction, "\\r\\FunctionQuery"))) {
            $mappingFunction = new RFunction(array(new RVar('_')), $mappingFunction);
        }
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $mappingFunction);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_CONCATMAP;
    }
}

class OrderBy extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $keys) {
        if (!is_array($keys))
            $keys = array($keys);
        // Check keys and convert strings
        foreach ($keys as &$val) {
            if (!is_string($val) && !(is_object($val) && is_subclass_of($val, "\\r\\Ordering"))) throw new RqlDriverError("Not a string or Ordering: " . $val);
            if (is_string($val)) {
                $val = new StringDatum($val);
            }
            unset($val);
        }
        
        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($keys as $key) {
            $this->setPositionalArg($i++, $key);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_ORDERBY;
    }
}

class Skip extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $n) {
        if (!(is_object($n) && is_subclass_of($n, "\\r\\Query")))
            $n = new NumberDatum($n);
            
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $n);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SKIP;
    }
}

class Limit extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $n) {
        if (!(is_object($n) && is_subclass_of($n, "\\r\\Query")))
            $n = new NumberDatum($n);
            
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $n);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_LIMIT;
    }
}

class Slice extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $startIndex, $endIndex = null) {
        if (!(is_object($startIndex) && is_subclass_of($startIndex, "\\r\\Query")))
            $startIndex = new NumberDatum($startIndex);
        if (isset($endIndex) && !(is_object($endIndex) && is_subclass_of($endIndex, "\\r\\Query")))
            $endIndex = new NumberDatum($endIndex);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $startIndex);
        if (isset($endIndex))
            $this->setPositionalArg(2, $endIndex);
        else
            $this->setPositionalArg(2, new NumberDatum(-1));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SLICE;
    }
}

class Nth extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index) {
        if (!(is_object($index) && is_subclass_of($index, "\\r\\Query")))
            $index = new NumberDatum($index);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_NTH;
    }
}

class Union extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence) {        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $otherSequence);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_UNION;
    }
}

?>
