<?php namespace r;

class InnerJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate) {
        if (!(is_object($predicate) && is_subclass_of($predicate, "\\r\\Query")))
            $predicate = nativeToFunction($predicate);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $otherSequence);
        $this->setPositionalArg(2, $predicate);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INNER_JOIN;
    }
}

class OuterJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate) {
        if (!(is_object($predicate) && is_subclass_of($predicate, "\\r\\Query")))
            $predicate = nativeToFunction($predicate);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $otherSequence);
        $this->setPositionalArg(2, $predicate);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_OUTER_JOIN;
    }
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
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attribute);
        $this->setPositionalArg(2, $otherSequence);
        if (isset($index))
            $this->setOptionalArg('index', $index);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_EQ_JOIN;
    }
}

class Zip extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->setPositionalArg(0, $sequence);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_ZIP;
    }
}

?>
