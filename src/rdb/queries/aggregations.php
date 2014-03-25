<?php namespace r;

class Reduce extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $reductionFunction) {
        $reductionFunction = nativeToFunction($reductionFunction);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $reductionFunction);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_REDUCE;
    }
}

class Count extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $filter = null) {
        if (isset($filter)) {
            $filter = nativeToDatumOrFunction($filter);
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

class Group extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $groupOn) {
        if (!is_array($groupOn)) {
            $groupOn = array($groupOn);
        }
        if (isset($groupOn['index'])) {
            $this->setOptionalArg('index', nativeToDatum($groupOn['index']));
            unset($groupOn['index']);
        }
        
        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($groupOn as $g) {
            $this->setPositionalArg($i++, nativeToDatumOrFunction($g));
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GROUP;
    }
}

class Ungroup extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->setPositionalArg(0, $sequence);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_UNGROUP;
    }
}

class Sum extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute = null) {
        if (isset($attribute)) {
            $attribute = nativeToDatumOrFunction($attribute);
        }
    
        $this->setPositionalArg(0, $sequence);
        if (isset($attribute)) {
            $this->setPositionalArg(1, $attribute);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SUM;
    }
}

class Avg extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute = null) {
        if (isset($attribute)) {
            $attribute = nativeToDatumOrFunction($attribute);
        }
    
        $this->setPositionalArg(0, $sequence);
        if (isset($attribute)) {
            $this->setPositionalArg(1, $attribute);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_AVG;
    }
}

class Min extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute = null) {
        if (isset($attribute)) {
            $attribute = nativeToDatumOrFunction($attribute);
        }
    
        $this->setPositionalArg(0, $sequence);
        if (isset($attribute)) {
            $this->setPositionalArg(1, $attribute);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_MIN;
    }
}

class Max extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute = null) {
        if (isset($attribute)) {
            $attribute = nativeToDatumOrFunction($attribute);
        }
    
        $this->setPositionalArg(0, $sequence);
        if (isset($attribute)) {
            $this->setPositionalArg(1, $attribute);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_MAX;
    }
}

class Contains extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        $value = nativeToDatumOrFunction($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_CONTAINS;
    }
}

?>
