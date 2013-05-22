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
        }
        
        $this->sequence = $sequence;
        $this->attributes = $attributes;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_PLUCK);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $i = 1;
        foreach ($this->attributes as $attr)
            $term->set_args($i++, $attr->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $attributes;
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
        }
        
        $this->sequence = $sequence;
        $this->attributes = $attributes;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_WITHOUT);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $i = 1;
        foreach ($this->attributes as $attr)
            $term->set_args($i++, $attr->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $attributes;
}

class Merge extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $other) {
        if (!(is_object($other) && is_subclass_of($other, "\\r\\Query")))
            $other = nativeToDatum($other);
        
        $this->sequence = $sequence;
        $this->other = $other;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_MERGE);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->other->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $other;
}

class Append extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value) {
        if (!(is_object($value) && is_subclass_of($value, "\\r\\Query")))
            $value = nativeToDatum($value);
        
        $this->sequence = $sequence;
        $this->value = $value;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_APPEND);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->value->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $value;
}

class Getattr extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute) {
        if (!(is_object($attribute) && is_subclass_of($attribute, "\\r\\Query")))
            $attribute = new StringDatum($attribute);
        
        $this->sequence = $sequence;
        $this->attribute = $attribute;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_GETATTR);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->attribute->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $attribute;
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
        }
        
        $this->sequence = $sequence;
        $this->attributes = $attributes;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_CONTAINS);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $i = 1;
        foreach ($this->attributes as $attr)
            $term->set_args($i++, $attr->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $attributes;
}


?>
