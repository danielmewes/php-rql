<?php namespace r;

class Insert extends ValuedQuery
{
    public function __construct(Table $table, $document, $opts = null) {
        if (isset($opts) && !\is_array($opts)) throw new RqlDriverError("Options must be an array.");
        if (!(is_object($document) && is_subclass_of($document, "\\r\\Query"))) {
            $json = tryEncodeAsJson($document);
            if ($json !== false) {
                $document = new Json($json);
            } else {
                $document = nativeToDatum($document);
            }
        }
        
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $document);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, nativeToDatum($val));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INSERT;
    }
}

class Update extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $delta, $opts = null) {
        if (isset($opts) && !\is_array($opts)) throw new RqlDriverError("Options must be an array.");
        if (!(is_object($delta) && is_subclass_of($delta, "\\r\\Query"))) {
            try {
                $json = tryEncodeAsJson($delta);
                if ($json !== false) {
                    $delta = new Json($json);
                } else {
                    $delta = nativeToDatum($delta);
                }
                if (!is_subclass_of($delta, "\\r\\Datum")) {
                    // $delta is not a simple datum. Wrap it into a function:                
                    $delta = new RFunction(array(new RVar('_')), $delta);
                }
            } catch (RqlDriverError $e) {
                $delta = nativeToFunction($delta);
            }
        } else if (!(is_object($delta) && is_subclass_of($delta, "\\r\\FunctionQuery")) && !(is_object($delta) && is_subclass_of($delta, "\\r\\Datum"))) {
            $delta = new RFunction(array(new RVar('_')), $delta);
        }
        
        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $delta);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, nativeToDatum($val));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_UPDATE;
    }
}

class Delete extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $opts = null) {
        if (isset($opts) && !\is_array($opts)) throw new RqlDriverError("Options must be an array.");
        
        $this->setPositionalArg(0, $selection);
        
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, nativeToDatum($val));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DELETE;
    }
}

class Replace extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $delta, $opts) {
        if (isset($opts) && !\is_array($opts)) throw new RqlDriverError("Options must be an array.");
        if (!(is_object($delta) && is_subclass_of($delta, "\\r\\Query"))) {
            // If we can make it an object, we will wrap that object into a function.
            // Otherwise, we will try to make it a function.
            try {
                $json = tryEncodeAsJson($delta);
                if ($json !== false) {
                    $delta = new Json($json);
                } else {
                    $delta = nativeToDatum($delta);
                    $delta = new RFunction(array(new RVar('_')), $delta);
                }
            } catch (RqlDriverError $e) {
                $delta = nativeToFunction($delta);
            }
        }
        
        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $delta);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, nativeToDatum($val));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_REPLACE;
    }
}

class Sync extends ValuedQuery
{
    public function __construct(Table $table) {
        $this->setPositionalArg(0, $table);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_SYNC;
    }
}

?>
