<?php namespace r;

class Map extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $mappingFunction) {
        if (!is_subclass_of($mappingFunction, "\\r\\Query")) {
            $mappingFunction = nativeToFunction($mappingFunction);
        } else if (!is_subclass_of($mappingFunction, "\\r\\FunctionQuery")) {
            $mappingFunction = new RFunction(array(new RVar('_')), $mappingFunction);
        }
        $this->sequence = $sequence;
        $this->mappingFunction = $mappingFunction;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_MAP);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->mappingFunction->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $mappingFunction;
}

class ConcatMap extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $mappingFunction) {
        if (!is_subclass_of($mappingFunction, "\\r\\Query")) {
            $mappingFunction = nativeToFunction($mappingFunction);
        } else if (!is_subclass_of($mappingFunction, "\\r\\FunctionQuery")) {
            $mappingFunction = new RFunction(array(new RVar('_')), $mappingFunction);
        }
        $this->sequence = $sequence;
        $this->mappingFunction = $mappingFunction;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_CONCATMAP);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->mappingFunction->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $mappingFunction;
}

class OrderBy extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $keys) {
        if (!is_array($keys))
            $keys = array($keys);
        // Check keys and convert strings
        foreach ($keys as &$val) {
            if (!is_string($val) && !is_subclass_of($val, "\\r\\Ordering")) throw new RqlDriverError("Not a string or Ordering: " . $val);
            if (is_string($val)) {
                $val = new StringDatum($val);
            }
        }
        
        $this->sequence = $sequence;
        $this->keys = $keys;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_ORDERBY);
        $term->set_args(0, $this->sequence->getPBTerm());
        $i = 1;
        foreach ($this->keys as $key) {
            $term->set_args($i++, $key->getPBTerm());
        }
        return $term;
    }
    
    private $sequence;
    private $keys;
}

class Skip extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $n) {
        if (!is_subclass_of($n, "\\r\\Query"))
            $n = new NumberDatum($n);
        $this->sequence = $sequence;
        $this->n = $n;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_SKIP);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->n->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $n;
}

class Limit extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $n) {
        if (!is_subclass_of($n, "\\r\\Query"))
            $n = new NumberDatum($n);
        $this->sequence = $sequence;
        $this->n = $n;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_LIMIT);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->n->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $n;
}

class Slice extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $startIndex, $endIndex = null) {
        if (!is_subclass_of($startIndex, "\\r\\Query"))
            $startIndex = new NumberDatum($startIndex);
        if (isset($endIndex) && !is_subclass_of($endIndex, "\\r\\Query"))
            $endIndex = new NumberDatum($endIndex);
        $this->sequence = $sequence;
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_SLICE);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->startIndex->getPBTerm());
        if (isset($endIndex))
            $term->set_args(2, $this->endIndex->getPBTerm());
        else {
            $subDatum = new NumberDatum(-1);
            $term->set_args(2, $subDatum->getPBTerm());
        }
        return $term;
    }
    
    private $sequence;
    private $startIndex;
    private $endIndex;
}

class Nth extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index) {
        if (!is_subclass_of($index, "\\r\\Query"))
            $index = new NumberDatum($index);
        $this->sequence = $sequence;
        $this->index = $index;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_NTH);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->index->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $index;
}

class Union extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence) {
        $this->sequence = $sequence;
        $this->otherSequence = $otherSequence;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_UNION);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->otherSequence->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $otherSequence;
}

?>
