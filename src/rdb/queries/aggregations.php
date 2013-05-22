<?php namespace r;

class Reduce extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $reductionFunction, $base) {
        if (!(is_object($reductionFunction) && is_subclass_of($reductionFunction, "\\r\\Query")))
            $reductionFunction = nativeToFunction($reductionFunction);
        if (isset($base) && !(is_object($base) && is_subclass_of($base, "\\r\\Query")))
            $base = nativeToDatum($base);
        $this->sequence = $sequence;
        $this->reductionFunction = $reductionFunction;
        $this->base = $base;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_REDUCE);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->reductionFunction->_getPBTerm());
        if (isset($this->base)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("base");
            $pair->set_val($this->base->_getPBTerm());
            $term->set_optargs(0, $pair);
        }
        return $term;
    }
    
    private $sequence;
    private $reductionFunction;
    private $base;
}

class Count extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->sequence = $sequence;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_COUNT);
        $term->set_args(0, $this->sequence->_getPBTerm());
        return $term;
    }
    
    private $sequence;
}

class Distinct extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->sequence = $sequence;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_DISTINCT);
        $term->set_args(0, $this->sequence->_getPBTerm());
        return $term;
    }
    
    private $sequence;
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
        $this->sequence = $sequence;
        $this->grouping = $grouping;
        $this->mapping = $mapping;
        $this->reduction = $reduction;
        $this->base = $base;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_GROUPED_MAP_REDUCE);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->grouping->_getPBTerm());
        $term->set_args(2, $this->mapping->_getPBTerm());
        $term->set_args(3, $this->reduction->_getPBTerm());
        if (isset($this->base)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("base");
            $pair->set_val($this->base->_getPBTerm());
            $term->set_optargs(0, $pair);
        }
        return $term;
    }
    
    private $sequence;
    private $grouping;
    private $mapping;
    private $reduction;
    private $base;
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
        
        $this->sequence = $sequence;
        $this->keys = $keys;
        $this->reductionObject = $reductionObject;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_GROUPBY);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $subTerm = new ArrayDatum($this->keys);
        $term->set_args(1, $subTerm->_getPBTerm());
        $term->set_args(2, $this->reductionObject->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $keys;
    private $reductionObject;
}

?>
