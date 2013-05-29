<?php namespace r;

require_once("util.php");

abstract class Query
{    
    abstract protected function getTermType();
    
    protected function setOptionalArg($key, Query $val) {
        if (!is_string($key)) throw new RqlDriverError("Internal driver error: Got a non-string key for an optional argument.");
        $this->optionalArgs[$key] = $val;
    }

    protected function setPositionalArg($pos, Query $arg) {
        if (!is_numeric($pos)) throw new RqlDriverError("Internal driver error: Got a non-numeric position for a positional argument.");
        $this->positionalArgs[$pos] = $arg;
    }

    public function _getPBTerm() {
        $term = new pb\Term();
        $term->setType($this->getTermType());
        foreach ($this->positionalArgs as $i => $arg) {
            $term->appendArgs($arg->_getPBTerm());
        }
        foreach ($this->optionalArgs as $key => $val) {
            $pair = new pb\Term_AssocPair();
            $pair->setKey($key);
            $pair->setVal($val->_getPBTerm());
            $term->appendOptargs($pair);
        }
        return $term;
    }
    
    public function run(Connection $connection, $options = null) {
        return $connection->_run($this, $options);
    }
    
    public function info() {
        return new Info($this);
    }
    
    public function __toString() {
        $backtrace = null;
        return $this->_toString($backtrace);
    }
    
    public function _toString(&$backtrace) {
        // TODO (daniel): This kind of printing backtraces is pretty hacky. Overhaul this.
        //  Maybe we could generate a PHP backtrace structure...
    
        $backtraceFrame = null;
        if (isset($backtrace) && $backtrace !== false) {
            $backtraceFrame = $backtrace->_consumeFrame();
        }
    
        $types = (new \ReflectionObject(new pb\Term_TermType()));
        $types = $types->getConstants();
        $type = "UNKNOWN";
        foreach ($types as $key => $val) {
            if (substr($key, 0, 3) != "PB_") continue;
            if ($val == $this->getTermType()) {
                $type = substr($key, 3);
            }
        }
        
        $argList = "";
        foreach ($this->positionalArgs as $i => $arg) {
            if ($i > 0) {
                if (isset($backtrace))
                    $argList .= "  ";
                else
                    $argList .= ", ";
            }
                
            $subTrace = is_null($backtrace) ? null : false;
            if (is_object($backtraceFrame) && $backtraceFrame->isPositionalArg() && $backtraceFrame->getPositionalArgPosition() == $i) {
                $subTrace = $backtrace;
            }
            $argList .= $arg->_toString($subTrace);
        }
        
        $optArgList = "";
        $firstOptArg = true;
        foreach ($this->optionalArgs as $key => $val) {
            if (!$firstOptArg) {
                if (isset($backtrace))
                    $optArgList .= "  ";
                else
                    $optArgList .= ", ";
            }
            $firstOptArg = false;
                
            $subTrace = is_null($backtrace) ? null : false;
            if (is_object($backtraceFrame) && $backtraceFrame->isOptionalArg() && $backtraceFrame->getOptionalArgName() == $key) {
                $subTrace = $backtrace;
            }
            if (isset($backtrace))
                $optArgList .= str_repeat(" ", strlen($key)) . "    " . $val->_toString($subTrace);
            else
                $optArgList .= $key . " => " . $val->_toString($subTrace);
        }
        
        if ($optArgList) {
            if (strlen($argList) > 0) {
                if (isset($backtrace))
                    $argList .= "  ";
                else
                    $argList .= ", ";
            }
            if (isset($backtrace))
                $argList .= "        " . $optArgList . " ";
            else
                $argList .= "OptArgs(" . $optArgList . ")";
        }
        
        $result = $type . "(" . $argList . ")";
        if (isset($backtrace)) {
            if ($backtraceFrame === false) {
                // We are the origin of the trouble
                return str_repeat("~", strlen($result));
            }
            else {
                return str_repeat(" ", strlen($type)) . " " . $argList . " ";
            }
        } else {
            return $result;
        }
    }
    
    private $positionalArgs = array();
    private $optionalArgs = array();
}

// This is just any query except for Table and Db at the moment.
// We simply define all remaining operations on this.
abstract class ValuedQuery extends Query
{
    public function update($delta, $nonAtomic = null) {
        return new Update($this, $delta, $nonAtomic);
    }
    public function delete() {
        return new Delete($this);
    }
    public function replace($delta, $nonAtomic = null) {
        return new Replace($this, $delta, $nonAtomic);
    }
    public function between($leftBound, $rightBound, $index = null) {
        return new Between($this, $leftBound, $rightBound, $index);
    }
    public function filter($predicate) {
        return new Filter($this, $predicate);
    }
    public function innerJoin(ValuedQuery $otherSequence, $predicate) {
        return new InnerJoin($this, $otherSequence, $predicate);
    }
    public function outerJoin(ValuedQuery $otherSequence, $predicate) {
        return new OuterJoin($this, $otherSequence, $predicate);
    }
    public function eqJoin($attribute, ValuedQuery $otherSequence, $index = null) {
        return new EqJoin($this, $attribute, $otherSequence, $index);
    }
    public function zip() {
        return new Zip($this);
    }
    public function map($mappingFunction) {
        return new Map($this, $mappingFunction);
    }
    public function concatMap($mappingFunction) {
        return new ConcatMap($this, $mappingFunction);
    }
    public function orderBy($keys) {
        return new OrderBy($this, $keys);
    }
    public function skip($n) {
        return new Skip($this, $n);
    }
    public function limit($n) {
        return new Limit($this, $n);
    }
    public function slice($startIndex, $endIndex = null) {
        return new Slice($this, $startIndex, $endIndex);
    }
    public function nth($index) {
        return new Nth($this, $index);
    }
    public function union(ValuedQuery $otherSequence) {
        return new Union($this, $otherSequence);
    }
    public function reduce($reductionFunction, $base = null) {
        return new Reduce($this, $reductionFunction, $base);
    }
    public function count() {
        return new Count($this);
    }
    public function distinct() {
        return new Distinct($this);
    }
    public function groupedMapReduce($grouping, $mapping, $reduction, $base = null) {
        return new GroupedMapReduce($this, $grouping, $mapping, $reduction, $base);
    }
    // RethinkDB in 1.4 expects a MakeObject term as the reduction object.
    // An ordinary ObjectDatum doesn't work. (this has been fixed in 1.5 though)
    public function groupBy($keys, MakeObject $reductionObject) {
        return new GroupBy($this, $keys, $reductionObject);
    }
    public function pluck($attributes) {
        return new Pluck($this, $attributes);
    }
    public function without($attributes) {
        return new Without($this, $attributes);
    }
    public function merge($other) {
        return new Merge($this, $other);
    }
    public function append($value) {
        return new Append($this, $value);
    }
    public function __invoke($attribute) {
        return new Getattr($this, $attribute);
    }
    public function attr($attribute) {
        return new Getattr($this, $attribute);
    }
    public function contains($attributes) {
        return new Contains($this, $attributes);
    }
    public function add($other) {
        return new Add($this, $other);
    }
    public function sub($other) {
        return new Sub($this, $other);
    }
    public function mul($other) {
        return new Mul($this, $other);
    }
    public function div($other) {
        return new Div($this, $other);
    }
    public function mod($other) {
        return new Mod($this, $other);
    }
    public function rAnd($other) {
        return new RAnd($this, $other);
    }
    public function rOr($other) {
        return new ROr($this, $other);
    }
    public function eq($other) {
        return new Eq($this, $other);
    }
    public function ne($other) {
        return new Ne($this, $other);
    }
    public function gt($other) {
        return new Gt($this, $other);
    }
    public function ge($other) {
        return new Ge($this, $other);
    }
    public function lt($other) {
        return new Lt($this, $other);
    }
    public function le($other) {
        return new Le($this, $other);
    }
    public function not() {
        return new Not($this);
    }
    public function rForeach($queryFunction) {
        return new RForeach($this, $queryFunction);
    }
    public function coerceTo($typeName) {
        return new CoerceTo($this, $typeName);
    }
    public function typeOf() {
        return new TypeOf($this);
    }
    public function rDo($inExpr)
    {
        return new RDo($this, $inExpr);
    }
}

abstract class Ordering extends Query {
}

class Asc extends Ordering {
    public function __construct($attribute) {
        $attribute = new StringDatum($attribute);
        $this->setPositionalArg(0, $attribute);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_ASC;
    }
}

class Desc extends Ordering {
    public function __construct($attribute) {
        $attribute = new StringDatum($attribute);
        $this->setPositionalArg(0, $attribute);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DESC;
    }
}

class ImplicitVar extends ValuedQuery
{
    protected function getTermType() {
        return pb\Term_TermType::PB_IMPLICIT_VAR;
    }
}

class Info extends ValuedQuery {
    public function __construct(Query $onQuery) {
        $this->setPositionalArg(0, $onQuery);
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INFO;
    }
}

class Cursor implements \Iterator
{
    // PHP iterator interface
    public function rewind() {
        if ($this->wasIterated) {
            throw new RqlDriverError("Rewind() not supported. You can only iterate over a cursor once.");
        }
    }
    public function next() {
        if (!$this->valid()) throw new RqlDriverError("No more data available.");
        $this->wasIterated = true;
        $this->currentIndex++;
        if ($this->currentIndex == $this->currentSize) {
            // We are at the end of currentData. Request new if available
            if (!$this->isComplete)
                $this->requestNewBatch();
        }
    }
    public function valid() {
        return !$this->isComplete || ($this->currentIndex < $this->currentSize);
    }
    public function key() {
        return null;
    }
    public function current() {
        if (!$this->valid()) throw new RqlDriverError("No more data available.");
        return $this->currentData[$this->currentIndex];
    }
    
    public function toArray() {
        $result = array();
        foreach ($this as $val) {
            $result[] = $val;
        }
        return $result;
    }
    
    public function toNative() {
        $vals = $this->toArray();
        foreach ($vals as &$val) {
            $val = $val->toNative();
            unset ($val);
        }
        return $vals;
    }
    
    public function __toString() {
        return "Cursor";
    }

    public function __construct(Connection $connection, pb\Response $initialResponse) {
        $this->connection = $connection;
        $this->token = $initialResponse->getToken();
        $this->wasIterated = false;
        
        $this->setBatch($initialResponse);
    }
    
    public function __destruct() {
        if (!$this->isComplete && $this->connection->isOpen()) {
            // Cancel the request
            $this->connection->_stopQuery($this->token);
        }
    }
    
    private function requestNewBatch() {
        $response = $this->connection->_continueQuery($this->token);
        $this->setBatch($response);
    }
    
    private function setBatch(pb\Response $response) {
        $this->isComplete = $response->getType() == pb\Response_ResponseType::PB_SUCCESS_SEQUENCE;
        $this->currentIndex = 0;
        $this->currentSize = $response->getResponseCount();
        $this->currentData = array();
        for ($i = 0; $i < $this->currentSize; ++$i) {
            $datum = protobufToDatum($response->getResponseAt($i));
            $this->currentData[$i] = $datum;
        }
    }

    private $token;
    private $connection;
    private $currentData;
    private $currentSize;
    private $currentIndex;
    private $isComplete;
    private $wasIterated;
}

?>
