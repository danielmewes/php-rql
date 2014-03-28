<?php namespace r;

class InnerJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate) {
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
    public function __construct(ValuedQuery $sequence, $attribute, ValuedQuery $otherSequence, $opts = null) {
        $attribute = nativeToDatumOrFunction($attribute);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attribute);
        $this->setPositionalArg(2, $otherSequence);
        if (isset($opts) && is_string($opts)) $opts = array('index' => $opts); // Backwards-compatibility
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
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
