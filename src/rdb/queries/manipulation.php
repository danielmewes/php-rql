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
        
        $this->sequence = $sequence;
        $this->attributes = $attributes;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_PLUCK);
        $term->set_args(0, $this->sequence->getPBTerm());
        $i = 1;
        foreach ($this->attributes as $attr)
            $term->set_args($i++, $attr->getPBTerm());
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
            unset($val);
        }
        
        $this->sequence = $sequence;
        $this->attributes = $attributes;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_WITHOUT);
        $term->set_args(0, $this->sequence->getPBTerm());
        $i = 1;
        foreach ($this->attributes as $attr)
            $term->set_args($i++, $attr->getPBTerm());
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
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_MERGE);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->other->getPBTerm());
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
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_APPEND);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->value->getPBTerm());
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
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_GETATTR);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->attribute->getPBTerm());
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
            unset($val);
        }
        
        $this->sequence = $sequence;
        $this->attributes = $attributes;
    }
    
    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_CONTAINS);
        $term->set_args(0, $this->sequence->getPBTerm());
        $i = 1;
        foreach ($this->attributes as $attr)
            $term->set_args($i++, $attr->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $attributes;
}


?>
