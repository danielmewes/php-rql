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
        $this->name = $name;
        
        if (RVar::$nextVarId == (1 << 31) - 1)
            RVar::$nextVarId = 0; // TODO: This is not ideal. In very very very rare cases, it could lead to collisions.
        else
            ++RVar::$nextVarId;
        
        $this->setPositionalArg(0, new NumberDatum($this->id));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_VAR;
    }
    
    public function getId() {
        return $this->id;
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
        
        $this->setPositionalArg(0, new ArrayDatum($args));
        $this->setPositionalArg(1, $top);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_FUNC;
    }
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
