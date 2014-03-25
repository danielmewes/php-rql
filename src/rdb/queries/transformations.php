<?php namespace r;

class WithFields extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes) {
        // The same comment as in pluck applies.
        if (!is_array($attributes))
            $attributes = array($attributes);
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
        if (isset($keys['index'])) {
            $this->setOptionalArg('index', nativeToDatum($keys['index']));
            unset($keys['index']);
        }
        foreach ($keys as &$val) {
            if (!(is_object($val) && is_subclass_of($val, "\\r\\Ordering"))) {
                $val = nativeToDatumOrFunction($val);
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
        $n = nativeToDatum($n);
            
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
        $n = nativeToDatum($n);
            
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
        $startIndex = nativeToDatum($startIndex);
        if (isset($endIndex))
            $endIndex = nativeToDatum($endIndex);
        
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
        $index = nativeToDatum($index);

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
        $predicate = nativeToDatumOrFunction($predicate);

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
        $n = nativeToDatum($n);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $n);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SAMPLE;
    }
}

?>
