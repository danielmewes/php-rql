<?php

namespace r\Datum;

use r\DatumConverter;
use r\Datum\Datum;
use r\ValuedQuery\MakeArray;
use r\Datum\ArrayDatum;
use r\Exceptions\RqlDriverError;

class ArrayDatum extends Datum
{
    public function encodeServerRequest()
    {
        $term = new MakeArray(array_values($this->getValue()));
        return $term->encodeServerRequest();
    }

    public static function decodeServerResponse($json)
    {
        $jsonArray = array_values((array)$json);
        foreach ($jsonArray as &$val) {
            $val = DatumConverter::decodedJSONToDatum($val);
            unset($val);
        }
        $result = new ArrayDatum();
        $result->setValue($jsonArray);
        return $result;
    }

    public function setValue($val)
    {
        if (!is_array($val)) {
            throw new RqlDriverError("Not an array: " . $val);
        }
        foreach ($val as $v) {
            if (!(is_object($v) && is_subclass_of($v, "\\r\\Query"))) {
                throw new RqlDriverError("Not a Query: " . $v);
            }
        }
        parent::setValue($val);
    }

    public function toNative($opts)
    {
        $native = array();
        foreach ($this->getValue() as $val) {
            $native[] = $val->toNative($opts);
        }
        return $native;
    }

    public function __toString()
    {
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
