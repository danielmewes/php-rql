<?php namespace r;

class InnerJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate) {
        if (!is_subclass_of($predicate, "\\r\\Query"))
            $predicate = nativeToFunction($predicate);
        $this->sequence = $sequence;
        $this->otherSequence = $otherSequence;
        $this->predicate = $predicate;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_INNER_JOIN);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->otherSequence->getPBTerm());
        $term->set_args(2, $this->predicate->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $otherSequence;
    private $predicate;
}

class OuterJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate) {
        if (!is_subclass_of($predicate, "\\r\\Query"))
            $predicate = nativeToFunction($predicate);
        $this->sequence = $sequence;
        $this->otherSequence = $otherSequence;
        $this->predicate = $predicate;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_OUTER_JOIN);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->otherSequence->getPBTerm());
        $term->set_args(2, $this->predicate->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $otherSequence;
    private $predicate;
}

class EqJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute, ValuedQuery $otherSequence) {
        $attribute = new StringDatum($attribute);
        $this->sequence = $sequence;
        $this->attribute = $attribute;
        $this->otherSequence = $otherSequence;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_EQ_JOIN);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->attribute->getPBTerm());
        $term->set_args(2, $this->otherSequence->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $attribute;
    private $otherSequence;
}

class Zip extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->sequence = $sequence;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_ZIP);
        $term->set_args(0, $this->sequence->getPBTerm());
        return $term;
    }
    
    private $sequence;
}

?>
