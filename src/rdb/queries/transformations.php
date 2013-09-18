<?php namespace r;

class WithFields extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes) {
        // The same comment as in pluck applies.
        if (is_string($attributes))
            $attributes = array($attributes);
        if (!(is_object($attributes) && is_subclass_of($attributes, "\\r\\Query")))
            $attributes = nativeToDatum($attributes);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributes);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_WITH_FIELDS;
    }
}

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
            if (!(is_object($val) && is_subclass_of($val, "\\r\\Ordering"))) {
                if (!(is_object($val) && is_subclass_of($val, "\\r\\Query"))) {
                    try {
                        $val = nativeToDatum($val);
                        if (!is_subclass_of($val, "\\r\\Datum")) {
                            // $val is not a simple datum. Wrap it into a function:                
                            $val = new RFunction(array(new RVar('_')), $val);
                        }
                    } catch (RqlDriverError $e) {
                        $val = nativeToFunction($val);
                    }
                } else if (!(is_object($val) && is_subclass_of($val, "\\r\\FunctionQuery"))) {
                    $val = new RFunction(array(new RVar('_')), $val);
                }
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
    public function __construct(ValuedQuery $sequence, $startIndex, $endIndex = null, $opts = null) {
        if (!(is_object($startIndex) && is_subclass_of($startIndex, "\\r\\Query")))
            $startIndex = new NumberDatum($startIndex);
        if (isset($endIndex) && !(is_object($endIndex) && is_subclass_of($endIndex, "\\r\\Query")))
            $endIndex = new NumberDatum($endIndex);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $startIndex);
        if (isset($endIndex)) {
            $this->setPositionalArg(2, $endIndex);
        } else {
            $this->setPositionalArg(2, new NumberDatum(-1));
            $this->setOptionalArg('right_bound', new StringDatum('closed'));
        }
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
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

class IndexesOf extends ValuedQuery
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
        return pb\Term_TermType::PB_INDEXES_OF;
    }
}

class IsEmpty extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->setPositionalArg(0, $sequence);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_IS_EMPTY;
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

class Sample extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $n) {
        if (!(is_object($n) && is_subclass_of($n, "\\r\\Query")))
            $n = new NumberDatum($n);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $n);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SAMPLE;
    }
}

?>
