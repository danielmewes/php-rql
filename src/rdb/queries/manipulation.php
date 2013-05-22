<?php namespace r;

class Pluck extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes) {
        if (is_string($attributes))
            $attributes = array($attributes);
        if (!is_array($attributes)) throw new RqlDriverError("Attributes must be an array or a single attribute.");        
        // Check keys and convert strings
        foreach ($attributes as &$val) {
            $val = new StringDatum($val);
            unset($val);
        }
        
        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($attributes as $val) {
            $this->setPositionalArg($i++, $val);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_PLUCK;
    }
}

class Without extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes) {
        if (is_string($attributes))
            $attributes = array($attributes);
        if (!is_array($attributes)) throw new RqlDriverError("Attributes must be an array or a single attribute.");        
        // Check keys and convert strings
        foreach ($attributes as &$val) {
            $val = new StringDatum($val);
            unset($val);
        }
        
        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($attributes as $val) {
            $this->setPositionalArg($i++, $val);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_WITHOUT;
    }
}

class Merge extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $other) {
        if (!(is_object($other) && is_subclass_of($other, "\\r\\Query")))
            $other = nativeToDatum($other);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $other);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_MERGE;
    }
}

class Append extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        if (!(is_object($value) && is_subclass_of($value, "\\r\\Query")))
            $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_APPEND;
    }
}

class Getattr extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute) {
        if (!(is_object($attribute) && is_subclass_of($attribute, "\\r\\Query")))
            $attribute = new StringDatum($attribute);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attribute);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GETATTR;
    }
}

class Contains extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes) {
        if (is_string($attributes))
            $attributes = array($attributes);
        if (!is_array($attributes)) throw new RqlDriverError("Attributes must be an array or a single attribute.");        
        // Check keys and convert strings
        foreach ($attributes as &$val) {
            $val = new StringDatum($val);
            unset($val);
        }
        
        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($attributes as $val) {
            $this->setPositionalArg($i++, $val);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_CONTAINS;
    }
}


?>
