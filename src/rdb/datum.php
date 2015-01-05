<?php namespace r;

require_once("misc.php");
require_once("util.php");
require_once("function.php");

function nativeToDatum($v) {
    if (is_array($v) || (is_object($v) && get_class($v) == "stdClass")) {
        $datumArray = array();
        $hasNonNumericKey = false;
        $mustUseMakeTerm = false;
        if (is_object($v)) {
            // Handle "stdClass" objects
            $hasNonNumericKey = true; // Force conversion into an ObjectDatum
            $v = (array)$v;
        }
        foreach($v as $key => $val) {
            if (!is_numeric($key) && !is_string($key)) throw new RqlDriverError("Key must be a string.");
            if ((is_object($val) && is_subclass_of($val, "\\r\\Query")) && !(is_object($val) && is_subclass_of($val, "\\r\\Datum"))) {
                $subDatum = $val;
                $mustUseMakeTerm = true;
            } else {
                $subDatum = nativeToDatum($val);
                if (!is_subclass_of($subDatum, "\\r\\Datum"))
                    $mustUseMakeTerm = true;
            }
            if (is_string($key)) {   
                $hasNonNumericKey = true;
                $datumArray[$key] = $subDatum;
            } else {
                $datumArray[$key] = $subDatum;
            }
        }

        // Note: In the case of $hasNonNumericKey === false, we cannot
        //   know if we should convert to an array or an object. We
        //   currently assume array, but this is not overly clean.
        //   Of course the user always has the option to wrap data
        //   into a Datum manually.
        //   We use this behavior because it is consistent to json_encode,
        //   which we sometimes use as a transparent replacement for
        //   nativeToDatum().
        if ($hasNonNumericKey) {
            if ($mustUseMakeTerm) {
                return new MakeObject($datumArray);
            } else {
                return new ObjectDatum($datumArray);
            }
        } else {
            if ($mustUseMakeTerm) {
                return new MakeArray($datumArray);
            } else {
                return new ArrayDatum($datumArray);
            }
        }
    }
    else if (is_null($v)) {
        return new NullDatum();
    }
    else if (is_bool($v)) {
        return new BoolDatum($v);
    }
    else if (is_int($v) || is_float($v)) {
        return new NumberDatum($v);
    }
    else if (is_string($v)) {
        return new StringDatum($v);
    } else if (is_object($v) && is_subclass_of($v, "\\r\\Query")) {
        return $v;
    } else if (is_object($v) && is_subclass_of($v, "DateTimeInterface")) {
        $iso8601 = $v->format(\DateTime::ISO8601);
        return new Iso8601($iso8601);
    } else {
        throw new RqlDriverError("Unhandled type " . get_class($v));
    }
}

// ------------- Helpers -------------
function decodedJSONToDatum($json) {
    if (is_null($json)) return NullDatum::_fromJSON($json);
    if (is_bool($json)) return BoolDatum::_fromJSON($json);
    if (is_int($json) || is_float($json)) return NumberDatum::_fromJSON($json);
    if (is_string($json)) return StringDatum::_fromJSON($json);
    if (is_array($json)) return ArrayDatum::_fromJSON($json);
    if (is_object($json)) return ObjectDatum::_fromJSON($json);

    throw new RqlDriverError("Unhandled type " . get_class($json));
}

function tryEncodeAsJson($v) {
    if (canEncodeAsJson($v)) {
        // PHP by default loses some precision when encoding floats, so we temporarily
        // bump up the `precision` option to avoid this.
        // The 17 assumes IEEE-754 double precision numbers.
        // Source: http://docs.oracle.com/cd/E19957-01/806-3568/ncg_goldberg.html
        //         "The same argument applied to double precision shows that 17 decimal
        //          digits are required to recover a double precision number."
        $previousPrecision = ini_set("precision", 17);
        $json = json_encode($v);
        if ($previousPrecision !== false) {
            ini_set("precision", $previousPrecision);
        }
        if ($json === false) throw new RqlDriverError("Failed to encode document as JSON: " . json_last_error());
        return $json;
    } else {
        return false;
    }
}

function canEncodeAsJson($v) {
    if (is_array($v)) {
        foreach($v as $key => $val) {
            if (!is_numeric($key) && !is_string($key)) return false;
            if (!canEncodeAsJson($val)) return false;
        }
        return true;
    }
    else if (is_null($v)) {
        return true;
    }
    else if (is_bool($v)) {
        return true;
    }
    else if (is_int($v) || is_float($v)) {
        return true;
    }
    else if (is_string($v)) {
        return true;
    }
    else {
        return false;
    }
}

// ------------- RethinkDB make queries -------------
class MakeArray extends ValuedQuery
{
    public function __construct($value) {
        if (!is_array($value)) throw new RqlDriverError("Value must be an array.");
        $i = 0;
        foreach($value as $val) {
            $this->setPositionalArg($i++, $val);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_MAKE_ARRAY;
    }
}

class MakeObject extends ValuedQuery
{
    public function __construct($value) {
        if (!is_array($value)) throw new RqlDriverError("Value must be an array.");
        foreach($value as $key => $val) {
            $this->setOptionalArg($key, $val);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_MAKE_OBJ;
    }
}

// ------------- RethinkDB datum types -------------
abstract class Datum extends ValuedQuery
{
    public function __construct($value = null) {
        if (isset($value)) {
            $this->setValue($value);
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DATUM;
    }
    
    public function toNative() {
        return $this->getValue();
    }
    
    public function __toString() {
        return "" . $this->getValue();
    }
    
    public function _toString(&$backtrace) {
        $result = $this->__toString();
        if (is_null($backtrace)) return $result;
        else {
            if ($backtrace === false) return str_repeat(" ", strlen($result));
            $backtraceFrame = $backtrace->_consumeFrame();
            if ($backtraceFrame !== false) throw new RqlDriverError("Internal Error: The backtrace says that we should have an argument in a Datum. This is not possible.");
            return str_repeat("~", strlen($result));
        }
    }
        
    public function getValue() {
        return $this->value;
    }
    public function setValue($val) {
        $this->value = $val;
    }
    private $value;
}

class NullDatum extends Datum
{
    public function _getJSONTerm() {
        return null;
    }
    
    static public function _fromJSON($json) {
        $result = new NullDatum();
        $result->setValue(null);
        return $result;
    }
    
    public function setValue($val) {
        if (!is_null($val)) throw new RqlDriverError("Not null: " . $val);
        parent::setValue($val);
    }
    
    public function __toString() {
        return "null";
    }
}

class BoolDatum extends Datum
{
    public function _getJSONTerm() {
        return (bool)$this->getValue();
    }
    
    static public function _fromJSON($json) {
        $result = new BoolDatum();
        $result->setValue((bool)$json);
        return $result;
    }
    
    public function __toString() {
        if ($this->getValue()) return "true";
        else return "false";
    }
    
    public function setValue($val) {
        if (is_numeric($val)) $val = (($val == 0) ? false : true);
        if (!is_bool($val)) throw new RqlDriverError("Not a boolean: " . $val);
        parent::setValue($val);
    }
}

class NumberDatum extends Datum
{
    public function _getJSONTerm() {
        return (float)$this->getValue();
    }
    
    static public function _fromJSON($json) {
        $result = new NumberDatum();
        $result->setValue((float)$json);
        return $result;
    }
    
    public function setValue($val) {
        if (!is_numeric($val)) throw new RqlDriverError("Not a number: " . $val);
        parent::setValue($val);
    }
}

class StringDatum extends Datum
{
    public function _getJSONTerm() {
        return (string)$this->getValue();
    }

    static public function _fromJSON($json) {
        $result = new StringDatum();
        $result->setValue((string)$json);
        return $result;
    }
    
    public function setValue($val) {
        if (!is_string($val)) throw new RqlDriverError("Not a string");
        parent::setValue($val);
    }
    
    public function __toString() {
        return "'" . $this->getValue() . "'";
    }
}

class ArrayDatum extends Datum
{
    public function _getJSONTerm() {
        $term = new MakeArray(array_values($this->getValue()));
        return $term->_getJSONTerm();
    }
    
    static public function _fromJSON($json) {
        $jsonArray = array_values((array)$json);
        foreach ($jsonArray as &$val)  {
            $val = decodedJSONToDatum($val);
            unset($val);
        }
        $result = new ArrayDatum();
        $result->setValue($jsonArray);
        return $result;
    }
    
    public function setValue($val) {
        if (!is_array($val)) throw new RqlDriverError("Not an array: " . $val);
        foreach($val as $v) {
            if (!(is_object($v) && is_subclass_of($v, "\\r\\Query"))) throw new RqlDriverError("Not a Query: " . $v);
        }
        parent::setValue($val);
    }
    
    public function toNative() {
        $native = array();
        foreach ($this->getValue() as $val) {
            $native[] = $val->toNative();
        }
        return $native;
    }
    
    public function __toString() {
        $string = 'array(';
        $first = true;
        foreach ($this->getValue() as $val) {
            if (!$first) {
                $string .= ", ";
            }
            $first = false;
            $string .= $val;
        }
        $string .= ')';
        return $string;
    }
}

class ObjectDatum extends Datum
{
    public function _getJSONTerm() {
        $jsonValue = $this->getValue();
        foreach ($jsonValue as $key => &$val) {
            $val = $val->_getJSONTerm();
            unset($val);
        }
        return (Object)$jsonValue;
    }

    static public function _fromJSON($json) {
        $jsonObject = (array)$json;
        foreach ($jsonObject as $key => &$val)  {
            $val = decodedJSONToDatum($val);
            unset($val);
        }
        $result = new ObjectDatum();
        $result->setValue($jsonObject);
        return $result;
    }

    public function setValue($val) {
        if (!is_array($val)) throw new RqlDriverError("Not an array: " . $val);
        foreach($val as $k => $v) {
            if (!is_string($k) && !is_numeric($k)) throw new RqlDriverError("Not a string or number: " . $k);
            if (!(is_object($v) && is_subclass_of($v, "\\r\\Query"))) throw new RqlDriverError("Not a Query: " . $v);
        }
        parent::setValue($val);
    }

    public function toNative() {
        $native = array();
        foreach ($this->getValue() as $key => $val) {
            $native[$key] = $val->toNative();
        }
        // Decode BINARY pseudo-type
        if (isset($native['$reql_type$']) && $native['$reql_type$'] == 'BINARY') {
            $decodedStr = base64_decode($native['data'], true);
            if ($decodedStr === FALSE) {
                throw new RqlDriverError("Failed to Base64 decode r\\binary value '" . $native['data'] . "'");
            }
            return $decodedStr;
        }
        // Convert TIME to DateTime
        if (isset($native['$reql_type$']) && $native['$reql_type$'] == 'TIME') {
            $time = $native['epoch_time'];
            $format = (strpos($time, '.') !== false) ? '!U.u' : '!U';
            return \DateTime::createFromFormat($format, $time);
        }
        return $native;
    }

    public function __toString() {
        // Handle BINARY pseudo-type
        $val = $this->getValue();
        if (isset($val['$reql_type$']) && $val['$reql_type$']->getValue() == 'BINARY') {
            $decodedStr = base64_decode($val['data']->getValue(), true);
            if ($decodedStr === FALSE) {
                return "r\\binary(ERROR)";
            }
            return "r\\binary('$decodedStr')";
        }
        $string = 'array(';
        $first = true;
        foreach ($val as $key => $val) {
            if (!$first) {
                $string .= ", ";
            }
            $first = false;
            $string .= "'" . $key . "' => " . $val;
        }
        $string .= ')';
        return $string;
    }
}

?>
