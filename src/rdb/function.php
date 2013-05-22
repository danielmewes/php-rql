<?php namespace r;

require_once("misc.php");
require_once("util.php");

// Currently: Js and RFunction
abstract class FunctionQuery extends ValuedQuery {
}

class RVar extends ValuedQuery {
    public function __construct($name) {
        if (!is_string($name)) throw new RqlDriverError("Variable name must be a string.");
        $this->id = RVar::$nextVarId;
        ++RVar::$nextVarId;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_VAR);
        $subTerm = new NumberDatum($this->id);
        $term->set_args(0, $subTerm->_getPBTerm());
        return $term;
    }
    
    private $id;
    
    private static $nextVarId = 1;
}

class RFunction extends FunctionQuery {
    public function __construct($args, Query $top) {
        if (!is_array($args)) throw new RqlDriverError("Arguments must be an array.");
        foreach ($args as &$arg) {
            if (!is_a($arg, "\\r\\RVar")) throw new RqlDriverError("Arguments must be RVar variables.");
            $arg = new NumberDatum($arg->getId());
            unset($arg);
        }
        
        $this->args = $args;
        $this->top = $top;
    }
    
    public function _getPBTerm() {
        $term = new pb\Term();
        $term->set_type(pb\Term_TermType::PB_FUNC);
        $subTerm = new ArrayDatum($this->args);
        $term->set_args(0, $subTerm->_getPBTerm());
        $term->set_args(1, $this->top->_getPBTerm());
        return $term;
    }
    
    private $args;
    private $top;
}

function nativeToFunction($f) {
    $reflection = new \ReflectionFunction($f);
    
    $args = array();
    foreach ($reflection->getParameters() as $param) {
        $args[] = new RVar($param->getName());
    }
    $result = $reflection->invokeArgs($args);
    
    if (!(is_object($result) && is_subclass_of($result, "\\r\\Query"))) throw new RqlDriverError("The function did not evaluate to a query (missing return? missing r\expr(...)?).");

    return new RFunction($args, $result);
}

?>
