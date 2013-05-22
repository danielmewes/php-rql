<?php namespace r;

class InnerJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate) {
        if (!(is_object($predicate) && is_subclass_of($predicate, "\\r\\Query")))
            $predicate = nativeToFunction($predicate);
        $this->sequence = $sequence;
        $this->otherSequence = $otherSequence;
        $this->predicate = $predicate;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_INNER_JOIN);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->otherSequence->_getPBTerm());
        $term->set_args(2, $this->predicate->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $otherSequence;
    private $predicate;
}

class OuterJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate) {
        if (!(is_object($predicate) && is_subclass_of($predicate, "\\r\\Query")))
            $predicate = nativeToFunction($predicate);
        $this->sequence = $sequence;
        $this->otherSequence = $otherSequence;
        $this->predicate = $predicate;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_OUTER_JOIN);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->otherSequence->_getPBTerm());
        $term->set_args(2, $this->predicate->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $otherSequence;
    private $predicate;
}

class EqJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute, ValuedQuery $otherSequence, $index = null) {
        if (isset($index))
            $index = new StringDatum($index);
        $attribute = new StringDatum($attribute);
        $this->sequence = $sequence;
        $this->attribute = $attribute;
        $this->otherSequence = $otherSequence;
        $this->index = $index;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_EQ_JOIN);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->attribute->_getPBTerm());
        $term->set_args(2, $this->otherSequence->_getPBTerm());
        if (isset($this->index)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("index");
            $pair->set_val($this->index->_getPBTerm());
            $term->set_optargs(0, $pair);
        }
        return $term;
    }
    
    private $sequence;
    private $attribute;
    private $otherSequence;
    private $index;
}

class Zip extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->sequence = $sequence;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_ZIP);
        $term->set_args(0, $this->sequence->_getPBTerm());
        return $term;
    }
    
    private $sequence;
}

?>
