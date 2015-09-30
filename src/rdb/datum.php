<?php

namespace r;

require_once("function.php");

use r\Datum\NullDatum;
use r\Datum\BoolDatum;
use r\Datum\NumberDatum;
use r\Datum\StringDatum;
use r\Datum\ArrayDatum;
use r\Datum\ObjectDatum;
use r\ValuedQuery\MakeArray;
use r\ValuedQuery\MakeObject;
use r\Exceptions\RqlDriverError;
use r\Queries\Dates\Iso8601;

function nativeToDatum($v)
{
    if (is_array($v) || (is_object($v) && in_array(get_class($v), array("stdClass", "ArrayObject")))) {
        $datumArray = array();
        $hasNonNumericKey = false;
        $mustUseMakeTerm = false;
        if (is_object($v)) {
            // Handle "stdClass" objects
            $hasNonNumericKey = true; // Force conversion into an ObjectDatum
            $v = (array)$v;
        }
        foreach ($v as $key => $val) {
            if (!is_numeric($key) && !is_string($key)) {
                throw new RqlDriverError("Key must be a string.");
            }
            if ((is_object($val) && is_subclass_of($val, "\\r\\Query")) && !(is_object($val) && is_subclass_of($val, '\r\Datum\Datum'))) {
                $subDatum = $val;
                $mustUseMakeTerm = true;
            } else {
                $subDatum = nativeToDatum($val);
                if (!is_subclass_of($subDatum, '\r\Datum\Datum')) {
                    $mustUseMakeTerm = true;
                }
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
        //   \r\nativeToDatum().
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
    } elseif (is_null($v)) {
        return new NullDatum();
    } elseif (is_bool($v)) {
        return new BoolDatum($v);
    } elseif (is_int($v) || is_float($v)) {
        return new NumberDatum($v);
    } elseif (is_string($v)) {
        return new StringDatum($v);
    } elseif (is_object($v) && is_subclass_of($v, "\\r\\Query")) {
        return $v;
    } elseif (is_object($v) && (is_subclass_of($v, "DateTimeInterface") || is_a($v, "DateTime"))) {
        // PHP prior to 5.5.0 doens't have DateTimeInterface, so we test for DateTime directly as well ^^^^^
        $iso8601 = $v->format(\DateTime::ISO8601);
        return new Iso8601($iso8601);
    } else {
        throw new RqlDriverError("Unhandled type " . get_class($v));
    }
}

// ------------- Helpers -------------
function decodedJSONToDatum($json)
{
    if (is_null($json)) {
        return NullDatum::_fromJSON($json);
    }
    if (is_bool($json)) {
        return BoolDatum::_fromJSON($json);
    }
    if (is_int($json) || is_float($json)) {
        return NumberDatum::_fromJSON($json);
    }
    if (is_string($json)) {
        return StringDatum::_fromJSON($json);
    }
    if (is_array($json)) {
        return ArrayDatum::_fromJSON($json);
    }
    if (is_object($json)) {
        return ObjectDatum::_fromJSON($json);
    }

    throw new RqlDriverError("Unhandled type " . get_class($json));
}

function tryEncodeAsJson($v)
{
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
        if ($json === false) {
            throw new RqlDriverError("Failed to encode document as JSON: " . json_last_error());
        }
        return $json;
    } else {
        return false;
    }
}

function canEncodeAsJson($v)
{
    if (is_array($v)) {
        foreach ($v as $key => $val) {
            if (!is_numeric($key) && !is_string($key)) {
                return false;
            }
            if (!canEncodeAsJson($val)) {
                return false;
            }
        }
        return true;
    } elseif (is_null($v)) {
        return true;
    } elseif (is_bool($v)) {
        return true;
    } elseif (is_int($v) || is_float($v)) {
        return true;
    } elseif (is_string($v)) {
        return true;
    } else {
        return false;
    }
}
