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
            if (!(is_object($filter) && is_subclass_of($filter, "\\r\\Query"))) {
                try {
                    $filter = nativeToDatum($filter);
                    if (!is_subclass_of($filter, "\\r\\Datum")) {
                        // $filter is not a simple datum. Wrap it into a function:                
                        $filter = new RFunction(array(new RVar('_')), $filter);
                    }
                } catch (RqlDriverError $e) {
                    $filter = nativeToFunction($filter);
                }
            } else if (!(is_object($filter) && is_subclass_of($filter, "\\r\\FunctionQuery"))) {
                $filter = new RFunction(array(new RVar('_')), $filter);
            }
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
        if (is_string($keys))
            $keys = array($keys);
        if (!(is_object($keys) && is_subclass_of($keys, "\\r\\Query")))
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
        if (!(is_object($value) && is_subclass_of($value, "\\r\\Query"))) {
            try {
                $value = nativeToDatum($value);
                if (!is_subclass_of($value, "\\r\\Datum")) {
                    // $value is not a simple datum. Wrap it into a function:                
                    $value = new RFunction(array(new RVar('_')), $value);
                }
            } catch (RqlDriverError $e) {
                $value = nativeToFunction($value);
            }
        } else if (!(is_object($value) && is_subclass_of($value, "\\r\\FunctionQuery"))) {
            $value = new RFunction(array(new RVar('_')), $value);
        }
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_CONTAINS;
    }
}

?>
