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
    public function __construct(ValuedQuery $sequence) {
        $this->setPositionalArg(0, $sequence);
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
    public function __construct(ValuedQuery $sequence, $grouping, $mapping, $reduction, $base = null) {
        if (!(is_object($grouping) && is_subclass_of($grouping, "\\r\\Query")))
            $grouping = nativeToFunction($grouping);
        if (!(is_object($mapping) && is_subclass_of($mapping, "\\r\\Query")))
            $mapping = nativeToFunction($mapping);
        if (!(is_object($reduction) && is_subclass_of($reduction, "\\r\\Query")))
            $reduction = nativeToFunction($reduction);
        if (isset($base) && !(is_object($base) && is_subclass_of($base, "\\r\\Query"))) {
            // Convert base automatically
            $base = nativeToDatum($base);
        }
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $grouping);
        $this->setPositionalArg(2, $mapping);
        $this->setPositionalArg(3, $reduction);
        if (isset($base)) {
            $this->setOptionalArg('base', $base);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GROUPED_MAP_REDUCE;
    }
}

class GroupBy extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $keys, MakeObject $reductionObject) {
        if (!is_array($keys))
            $keys = array($keys);
        // Check keys and convert strings
        foreach ($keys as &$val) {
            if (!is_string($val) && !(is_object($val) && is_subclass_of($val, "\\r\\Query"))) throw new RqlDriverError("Not a string or Query: " . $val);
            if (is_string($val)) {
                $val = new StringDatum($val);
            }
            unset($val);
        }
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, new ArrayDatum($keys));
        $this->setPositionalArg(2, $reductionObject);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GROUPBY;
    }
}

?>
