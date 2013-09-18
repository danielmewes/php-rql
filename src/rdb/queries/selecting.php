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
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GET;
    }
}

class GetAll extends ValuedQuery
{
    public function __construct(Table $table, $key, $opts = null) {
        if (!(is_object($key) && is_subclass_of($key, "\\r\\Query"))) {
            $key = nativeToDatum($key);
        }
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
        if (isset($opts) && is_string($opts)) $opts = array('index' => $opts); // Backwards-compatibility
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GET_ALL;
    }
}

class GetMultiple extends ValuedQuery
{
    public function __construct(Table $table, $keys, $opts = null) {
        if (!is_array($keys)) throw new RqlDriverError("Keys in GetMultiple must be an array.");
        foreach ($keys as &$key) {
            if (!(is_object($key) && is_subclass_of($key, "\\r\\Query"))) {
                $key = nativeToDatum($key);
            }
            unset($key);
        }
        $this->setPositionalArg(0, $table);
        $i = 1;
        foreach ($keys as $key) {
            $this->setPositionalArg($i++, $key);
        }
        if (isset($opts) && is_string($opts)) $opts = array('index' => $opts); // Backwards-compatibility
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GET_ALL;
    }
}

class Between extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $leftBound, $rightBound, $opts = null) {
        if (!(is_object($leftBound) && is_subclass_of($leftBound, "\\r\\Query"))) $leftBound = nativeToDatum($leftBound);
        if (!(is_object($rightBound) && is_subclass_of($rightBound, "\\r\\Query"))) $rightBound = nativeToDatum($rightBound);
        
        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $leftBound);
        $this->setPositionalArg(2, $rightBound);
        if (isset($opts) && is_string($opts)) $opts = array('index' => $opts); // Backwards-compatibility
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_BETWEEN;
    }
}

class Filter extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $predicate, $default = null) {
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
        if (isset($default) && !(is_object($default) && is_subclass_of($default, "\\r\\Query"))) {
            $default = nativeToDatum($default);
        }
        
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $predicate);
        if (isset($default))
            $this->setOptionalArg('default', $default);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_FILTER;
    }
}

?>
