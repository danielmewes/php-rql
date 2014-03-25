<?php namespace r;

class Reduce extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $reductionFunction, $base) {
        if (!(is_object($reductionFunction) && is_subclass_of($reductionFunction, "\\r\\Query")))
            $reductionFunction = nativeToFunction($reductionFunction);
        if (isset($base) && !(is_object($base) && is_subclass_of($base, "\\r\\Query")))
            $base = nativeToDatum($base);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $reductionFunction);
        if (isset($base)) {
            $this->setOptionalArg('base', $base);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_REDUCE;
    }
}

class Count extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $filter = null) {
        if (isset($filter)) {
            $filter = nativeToDatumOrFunction($filter);
        }
    
        $this->setPositionalArg(0, $sequence);
        if (isset($filter)) {
            $this->setPositionalArg(1, $filter);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_COUNT;
    }
}

class Distinct extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->setPositionalArg(0, $sequence);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DISTINCT;
    }
}

class GroupedMapReduce extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $grouping, $mapping, $reduction) {
        $grouping = nativeToFunction($grouping);
        $mapping = nativeToFunction($mapping);
        $reduction = nativeToFunction($reduction);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $grouping);
        $this->setPositionalArg(2, $mapping);
        $this->setPositionalArg(3, $reduction);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GROUPED_MAP_REDUCE;
    }
}

class GroupBy extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $keys, MakeObject $reductionObject) {
        if (is_string($keys))
            $keys = array($keys);
        $keys = nativeToDatum($keys);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $keys);
        $this->setPositionalArg(2, $reductionObject);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GROUPBY;
    }
}

class Contains extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        $value = nativeToDatumOrFunction($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_CONTAINS;
    }
}

?>
