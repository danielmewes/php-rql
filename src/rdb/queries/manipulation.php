<?php namespace r;

class Pluck extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes) {
        // It would appear that the new pattern-matching syntax in 1.7 would make this
        // a little cumbersome. The problem seems to be that we must distinguish 
        // pattern such as array('a' => true) from a list of field names such as
        // array('a', 'b').
        // Luckily it turns out, that the new interface also supports passing in a plain
        // ArrayDatum, which will be interpreted correctly. So we can just always
        // interpret arrays as patterns.
    
        if (!is_array($attributes))
            $attributes = array($attributes);
        $attributes = nativeToDatum($attributes);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributes);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_PLUCK;
    }
}

class Without extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes) {
        // See comment above about pluck. The same applies here.
        if (!is_array($attributes))
            $attributes = array($attributes);
        $attributes = nativeToDatum($attributes);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributes);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_WITHOUT;
    }
}

class Merge extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $other) {
        $other = nativeToDatumOrFunction($other);
        
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
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_APPEND;
    }
}

class Prepend extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_PREPEND;
    }
}

class Difference extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DIFFERENCE;
    }
}

class SetInsert extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SET_INSERT;
    }
}

class SetIntersection extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SET_INTERSECTION;
    }
}

class SetDifference extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SET_DIFFERENCE;
    }
}

class SetUnion extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SET_UNION;
    }
}

class GetField extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute) {
        if (!(is_object($attribute) && is_subclass_of($attribute, "\\r\\Query")))
            $attribute = new StringDatum($attribute);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attribute);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GET_FIELD;
    }
}

class HasFields extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes) {
        // See comment above about pluck. The same applies here.
        if (is_string($attributes))
            $attributes = array($attributes);
        $attributes = nativeToDatum($attributes);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributes);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_HAS_FIELDS;
    }
}

class InsertAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $value) {
        $index = nativeToDatum($index);
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
        $this->setPositionalArg(2, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INSERT_AT;
    }
}

class SpliceAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $value) {
        $index = nativeToDatum($index);
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
        $this->setPositionalArg(2, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SPLICE_AT;
    }
}

class DeleteAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $endIndex = null) {
        $index = nativeToDatum($index);
        if (isset($endIndex))
            $endIndex = nativeToDatum($endIndex);
        
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
        if (isset($endIndex)) {
            $this->setPositionalArg(2, $endIndex);
        } 
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DELETE_AT;
    }
}

class ChangeAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $value) {
        $index = nativeToDatum($index);
        $value = nativeToDatum($value);
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
        $this->setPositionalArg(2, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_CHANGE_AT;
    }
}

class Keys extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence) {
        $this->setPositionalArg(0, $sequence);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_KEYS;
    }
}


?>
