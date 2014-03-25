<?php namespace r;

class RDo extends ValuedQuery
{
    public function __construct($args, $inExpr) {
        $inExpr = nativeToFunction($inExpr);
        $this->setPositionalArg(0, $inExpr);
        
        $i = 1;
        if (!is_array($args)) $args = array($args);
        foreach ($args as &$arg) {
            if (!(is_object($arg) && is_subclass_of($arg, "\\r\\Query"))) {
                $arg = nativeToDatum($arg);
            }
            $this->setPositionalArg($i++, $arg);
            unset($arg);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_FUNCALL;
    }
}

class Branch extends ValuedQuery
{
    public function __construct(Query $test, $trueBranch, $falseBranch) {
        $trueBranch = nativeToDatumOrFunction($trueBranch);
        $falseBranch = nativeToDatumOrFunction($falseBranch);

        $this->setPositionalArg(0, $test);
        $this->setPositionalArg(1, $trueBranch);
        $this->setPositionalArg(2, $falseBranch);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_BRANCH;
    }
}

class RForeach extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $queryFunction) {
        $queryFunction = nativeToFunction($queryFunction);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $queryFunction);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_FOREACH;
    }
}

class Error extends ValuedQuery
{
    public function __construct($message = null) {
        if (isset($message)) {
            if (!(is_object($message) && is_subclass_of($message, "\\r\\Query")))
                $message = new StringDatum($message);
            $this->setPositionalArg(0, $message);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_ERROR;
    }
}

class RDefault extends ValuedQuery
{
    public function __construct(Query $query, $defaultCase) {
        $defaultCase = nativeToDatumOrFunction($defaultCase);

        $this->setPositionalArg(0, $query);
        $this->setPositionalArg(1, $defaultCase);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DEFAULT;
    }
}

class Js extends FunctionQuery
{
    public function __construct($code, $timeout = null) {
        if (isset($timeout))
            $timeout = new NumberDatum($timeout);
        if (!(is_object($code) && is_subclass_of($code, "\\r\\Query")))
            $code = new StringDatum($code);
            
        $this->setPositionalArg(0, $code);
        if (isset($timeout))
            $this->setOptionalArg('timeout', $timeout);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_JAVASCRIPT;
    }
}

class CoerceTo extends ValuedQuery
{
    public function __construct(ValuedQuery $value, $typeName) {
        if (!(is_object($typeName) && is_subclass_of($typeName, "\\r\\Query")))
            $typeName = new StringDatum($typeName);
            
        $this->setPositionalArg(0, $value);
        $this->setPositionalArg(1, $typeName);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_COERCE_TO;
    }
}

class TypeOf extends ValuedQuery
{
    public function __construct(ValuedQuery $value) {
        $this->setPositionalArg(0, $value);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TYPEOF;
    }
}

?>
