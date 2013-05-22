<?php namespace r;

class Get extends ValuedQuery
{
    public function __construct(Table $table, $key) {
        if (!(is_object($key) && is_subclass_of($key, "\\r\\Query"))) {
            if (is_numeric($key))
                $key = new NumberDatum($key);
            else
                $key = new StringDatum($key);
        }
        $this->table = $table;
        $this->key = $key;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_GET);
        $term->set_args(0, $this->table->_getPBTerm());
        $term->set_args(1, $this->key->_getPBTerm());
        return $term;
    }
    
    private $table;
    private $key;
}

class GetAll extends ValuedQuery
{
    public function __construct(Table $table, $key, $index = null) {
        if (isset($index))
            $index = new StringDatum($index);
        if (!(is_object($key) && is_subclass_of($key, "\\r\\Query"))) {
            $key = nativeToDatum($key);
        }
        $this->table = $table;
        $this->key = $key;
        $this->index = $index;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_GET_ALL);
        $term->set_args(0, $this->table->_getPBTerm());
        $term->set_args(1, $this->key->_getPBTerm());
        if (isset($this->index)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("index");
            $pair->set_val($this->index->_getPBTerm());
            $term->set_optargs(0, $pair);
        }
        return $term;
    }
    
    private $table;
    private $key;
    private $index;
}

class Between extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $leftBound, $rightBound, $index = null) {
        if (isset($index))
            $index = new StringDatum($index);
        if (!(is_object($leftBound) && is_subclass_of($leftBound, "\\r\\Query"))) $leftBound = nativeToDatum($leftBound);
        if (!(is_object($rightBound) && is_subclass_of($rightBound, "\\r\\Query"))) $rightBound = nativeToDatum($rightBound);
        $this->selection = $selection;
        $this->leftBound = $leftBound;
        $this->rightBound = $rightBound;
        $this->index = $index;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_BETWEEN);
        $term->set_args(0, $this->selection->_getPBTerm());
        $term->set_args(1, $this->leftBound->_getPBTerm());
        $term->set_args(2, $this->rightBound->_getPBTerm());
        if (isset($this->index)) {
            $pair = new pb\Term_AssocPair();
            $pair->set_key("index");
            $pair->set_val($this->index->_getPBTerm());
            $term->set_optargs(0, $pair);
        };
        return $term;
    }
    
    private $selection;
    private $leftBound;
    private $rightBound;
    private $index;
}

class Filter extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $predicate) {
        if (!(is_object($predicate) && is_subclass_of($predicate, "\\r\\Query"))) {
            try {
                $predicate = nativeToDatum($predicate);
                if (!is_subclass_of($predicate, "\\r\\Datum")) {
                    // $predicate is not a simple datum. Wrap it into a function:                
                    $predicate = new RFunction(array(new RVar('_')), $predicate);
                }
            } catch (RqlDriverError $e) {
                $predicate = nativeToFunction($predicate);
            }
        } else if (!(is_object($predicate) && is_subclass_of($predicate, "\\r\\FunctionQuery"))) {
            $predicate = new RFunction(array(new RVar('_')), $predicate);
        }
        $this->sequence = $sequence;
        $this->predicate = $predicate;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_FILTER);
        $term->set_args(0, $this->sequence->_getPBTerm());
        $term->set_args(1, $this->predicate->_getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $predicate;
}

?>
