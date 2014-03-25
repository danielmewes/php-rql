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
        return $connection->_run($this, $options, $profile);
    }
    
    public function profile(Connection $connection, $options = null, &$result = null) {
        if (!isset($options)) $options = array();
        $options['profile'] = true;
        $result = $connection->_run($this, $options, $profile);
        return $profile;
    }

    public function info() {
        return new Info($this);
    }
    public function rDefault($defaultCase)
    {
        return new RDefault($this, $defaultCase);
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
// We define all remaining operations on this.
abstract class ValuedQuery extends Query
{
    public function update($delta, $opts = null) {
        return new Update($this, $delta, $opts);
    }
    public function delete($opts = null) {
        return new Delete($this, $opts);
    }
    public function replace($delta, $opts = null) {
        return new Replace($this, $delta, $opts);
    }
    public function between($leftBound, $rightBound, $opts = null) {
        return new Between($this, $leftBound, $rightBound, $opts);
    }
    public function filter($predicate, $default = null) {
        return new Filter($this, $predicate, $default);
    }
    public function innerJoin(ValuedQuery $otherSequence, $predicate) {
        return new InnerJoin($this, $otherSequence, $predicate);
    }
    public function outerJoin(ValuedQuery $otherSequence, $predicate) {
        return new OuterJoin($this, $otherSequence, $predicate);
    }
    public function eqJoin($attribute, ValuedQuery $otherSequence, $opts = null) {
        return new EqJoin($this, $attribute, $otherSequence, $opts);
    }
    public function zip() {
        return new Zip($this);
    }
    public function withFields($attributes) {
        return new WithFields($this, $attributes);
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
    public function slice($startIndex, $endIndex = null, $opts = null) {
        return new Slice($this, $startIndex, $endIndex, $opts);
    }
    public function nth($index) {
        return new Nth($this, $index);
    }
    public function indexesOf($predicate) {
        return new IndexesOf($this, $predicate);
    }
    public function isEmpty() {
        return new IsEmpty($this);
    }
    public function union(ValuedQuery $otherSequence) {
        return new Union($this, $otherSequence);
    }
    public function sample($n) {
        return new Sample($this, $n);
    }
    public function reduce($reductionFunction) {
        return new Reduce($this, $reductionFunction);
    }
    public function count($filter = null) {
        return new Count($this, $filter);
    }
    public function distinct() {
        return new Distinct($this);
    }
    public function group($groupOn) {
        return new Group($this, $groupOn);
    }
    public function ungroup() {
        return new Ungroup($this);
    }
    public function avg($attribute = null) {
        return new Avg($this, $attribute);
    }
    public function sum($attribute = null) {
        return new Sum($this, $attribute);
    }
    public function min($attribute = null) {
        return new Min($this, $attribute);
    }
    public function max($attribute = null) {
        return new Max($this, $attribute);
    }
    // Note: The API docs suggest that as of 1.6, contains can accept multiple values.
    //  We do not support that for the time being.
    public function contains($value) {
        return new Contains($this, $value);
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
    public function prepend($value) {
        return new Prepend($this, $value);
    }
    public function difference($value) {
        return new Difference($this, $value);
    }
    public function setInsert($value) {
        return new SetInsert($this, $value);
    }
    public function setUnion($value) {
        return new SetUnion($this, $value);
    }
    public function setIntersection($value) {
        return new SetIntersection($this, $value);
    }
    public function setDifference($value) {
        return new SetDifference($this, $value);
    }
    public function __invoke($attribute) {
        return new GetField($this, $attribute);
    }
    // Deprecated as of 1.7.0. Use getField instead.
    public function attr($attribute) {
        return new GetField($this, $attribute);
    }
    public function getField($attribute) {
        return new GetField($this, $attribute);
    }
    public function hasFields($attributes) {
        return new HasFields($this, $attributes);
    }
    public function insertAt($index, $value) {
        return new InsertAt($this, $index, $value);
    }
    public function spliceAt($index, $value) {
        return new SpliceAt($this, $index, $value);
    }
    public function deleteAt($index, $endIndex = null) {
        return new DeleteAt($this, $index, $endIndex);
    }
    public function changeAt($index, $value) {
        return new changeAt($this, $index, $value);
    }
    public function keys() {
        return new Keys($this);
    }
    public function add($other) {
        return add($this, $other);
    }
    public function sub($other) {
        return sub($this, $other);
    }
    public function mul($other) {
        return mul($this, $other);
    }
    public function div($other) {
        return div($this, $other);
    }
    public function mod($other) {
        return mod($this, $other);
    }
    public function rAnd($other) {
        return rAnd($this, $other);
    }
    public function rOr($other) {
        return rOr($this, $other);
    }
    public function eq($other) {
        return eq($this, $other);
    }
    public function ne($other) {
        return ne($this, $other);
    }
    public function gt($other) {
        return gt($this, $other);
    }
    public function ge($other) {
        return ge($this, $other);
    }
    public function lt($other) {
        return lt($this, $other);
    }
    public function le($other) {
        return le($this, $other);
    }
    public function not() {
        return not($this);
    }
    public function match($expression) {
        return new Match($this, $expression);
    }
    public function upcase() {
        return new Upcase($this);
    }
    public function downcase() {
        return new Downcase($this);
    }
    public function split($separator = null, $maxSplits = null) {
        return new split($this, $separator, $maxSplits);
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
    public function rDo($inExpr) {
        return new RDo($this, $inExpr);
    }
    public function toEpochTime() {
        return new ToEpochTime($this);
    }
    public function toIso8601() {
        return new ToIso8601($this);
    }
    public function inTimezone($timezone) {
        return new InTimezone($this, $timezone);
    }
    public function timezone() {
        return new Timezone($this);
    }
    public function during($startTime, $endTime, $opts = null) {
        return new During($this, $startTime, $endTime, $opts);
    }
    public function date() {
        return new Date($this);
    }
    public function timeOfDay() {
        return new TimeOfDay($this);
    }
    public function year() {
        return new Year($this);
    }
    public function month() {
        return new Month($this);
    }
    public function day() {
        return new Day($this);
    }
    public function dayOfWeek() {
        return new DayOfWeek($this);
    }
    public function dayOfYear() {
        return new DayOfYear($this);
    }
    public function hours() {
        return new Hours($this);
    }
    public function minutes() {
        return new Minutes($this);
    }
    public function seconds() {
        return new Seconds($this);
    }
}

abstract class Ordering extends Query {
}

class Asc extends Ordering {
    public function __construct($attribute) {
        $attribute = nativeToDatumOrFunction($attribute);
        $this->setPositionalArg(0, $attribute);
    }

    protected function getTermType() {
        return pb\Term_TermType::PB_ASC;
    }
}

class Desc extends Ordering {
    public function __construct($attribute) {
        $attribute = nativeToDatumOrFunction($attribute);
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

class RObject extends ValuedQuery {
    public function __construct($object) {
        if (!is_array($object)) throw RqlDriverError("Argument to r\\Object must be an array.");
        $i = 0;
        foreach($object as $v) {
            $this->setPositionalArg($i++, nativeToDatum($v));
        }
    }

    protected function getTermType() {
        return pb\Term_TermType::PB_OBJECT;
    }
}

class Json extends ValuedQuery {
    public function __construct($json) {
        if (!(is_object($json) && is_subclass_of($json, "\\r\\Query"))) {
            if (!is_string($json)) throw new RqlDriverError("JSON must be a string.");
            $json = new StringDatum($json);
        }
        $this->setPositionalArg(0, $json);
    }

    protected function getTermType() {
        return pb\Term_TermType::PB_JSON;
    }
}

class Literal extends ValuedQuery {
    public function __construct($value) {
        if (!(is_object($value) && is_subclass_of($value, "\\r\\Query"))) {
            $value = nativeToDatum($value);
        }
        $this->setPositionalArg(0, $value);
    }

    protected function getTermType() {
        return pb\Term_TermType::PB_LITERAL;
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

    public function close() {
        if (!$this->isComplete) {
            // Cancel the request
            $this->connection->_stopQuery($this->token);
            $this->isComplete = true;
        }
        $this->currentIndex = 0;
        $this->currentSize = 0;
        $this->currentData = array();
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
        if ($this->connection->isOpen()) {
            // Cancel the request
            $this->close();
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
