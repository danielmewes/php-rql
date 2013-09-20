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
    public function __construct(ValuedQuery $sequence, $attribute, ValuedQuery $otherSequence, $opts = null) {
        if (!(is_object($attribute) && is_subclass_of($attribute, "\\r\\Query"))) {
            try {
                $attribute = nativeToDatum($attribute);
                if (!is_subclass_of($attribute, "\\r\\Datum")) {
                    // $attribute is not a simple datum. Wrap it into a function:                
                    $attribute = new RFunction(array(new RVar('_')), $attribute);
                }
            } catch (RqlDriverError $e) {
                $attribute = nativeToFunction($attribute);
            }
        } else if (!(is_object($attribute) && is_subclass_of($attribute, "\\r\\FunctionQuery"))) {
            $attribute = new RFunction(array(new RVar('_')), $attribute);
        }
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
