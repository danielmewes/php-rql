<?php namespace r;

class RDo extends ValuedQuery
{
    public function __construct($args, $inExpr) {
        if (!(is_object($inExpr) && is_subclass_of($inExpr, "\\r\\Query")))
            $inExpr = nativeToFunction($inExpr);
        if (!is_array($args)) $args = array($args);
        foreach ($args as &$arg) {
            if (!(is_object($arg) && is_subclass_of($arg, "\\r\\Query"))) {
                $arg = nativeToDatum($arg);
            }
        }
        $this->inExpr = $inExpr;
        $this->args = $args;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_FUNCALL);
        $term->set_args(0, $this->inExpr->getPBTerm());
        $i = 1;
        foreach ($this->args as $arg) {
            $term->set_args($i, $arg->getPBTerm());
            ++$i;
        }
        return $term;
    }
    
    private $inExpr;
    private $args;
}

class Branch extends ValuedQuery
{
    public function __construct(Query $test, $trueBranch, $falseBranch) {
        if (!(is_object($trueBranch) && is_subclass_of($trueBranch, "\\r\\Query"))) {
            try {
                $trueBranch = nativeToDatum($trueBranch);
            } catch (RqlDriverError $e) {
                $trueBranch = nativeToFunction($trueBranch);
            }
        }
        if (!(is_object($falseBranch) && is_subclass_of($falseBranch, "\\r\\Query"))) {
            try {
                $falseBranch = nativeToDatum($falseBranch);
            } catch (RqlDriverError $e) {
                $falseBranch = nativeToFunction($falseBranch);
            }
        }
        
        $this->trueBranch = $trueBranch;
        $this->falseBranch = $falseBranch;
        $this->test = $test;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_BRANCH);
        $term->set_args(0, $this->test->getPBTerm());
        $term->set_args(1, $this->trueBranch->getPBTerm());
        $term->set_args(2, $this->falseBranch->getPBTerm());
        return $term;
    }
    
    private $test;
    private $trueBranch;
    private $falseBranch;
}

class RForeach extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $queryFunction) {
        if (!(is_object($queryFunction) && is_subclass_of($queryFunction, "\\r\\Query")))
            $queryFunction = nativeToFunction($queryFunction);
        $this->sequence = $sequence;
        $this->queryFunction = $queryFunction;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_FOREACH);
        $term->set_args(0, $this->sequence->getPBTerm());
        $term->set_args(1, $this->queryFunction->getPBTerm());
        return $term;
    }
    
    private $sequence;
    private $queryFunction;
}

class Error extends ValuedQuery
{
    public function __construct($message) {
        if (!(is_object($message) && is_subclass_of($message, "\\r\\Query")))
            $message = new StringDatum($message);
        $this->message = $message;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_ERROR);
        $term->set_args(0, $this->message->getPBTerm());
        return $term;
    }
    
    private $message;
}

class Js extends FunctionQuery
{
    public function __construct($code) {
        if (!(is_object($code) && is_subclass_of($code, "\\r\\Query")))
            $code = new StringDatum($code);
        $this->code = $code;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_JAVASCRIPT);
        $term->set_args(0, $this->code->getPBTerm());
        return $term;
    }
    
    private $code;
}

class CoerceTo extends ValuedQuery
{
    public function __construct(ValuedQuery $value, $typeName) {
        if (!(is_object($typeName) && is_subclass_of($typeName, "\\r\\Query")))
            $typeName = new StringDatum($typeName);
        $this->value = $value;
        $this->typeName = $typeName;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_COERCE_TO);
        $term->set_args(0, $this->value->getPBTerm());
        $term->set_args(1, $this->typeName->getPBTerm());
        return $term;
    }
    
    private $value;
    private $typeName;
}

class TypeOf extends ValuedQuery
{
    public function __construct(ValuedQuery $value) {
        $this->value = $value;
    }

    public function getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_TYPEOF);
        $term->set_args(0, $this->value->getPBTerm());
        return $term;
    }
    
    private $value;
}

?>
