<?php

namespace r\Datum;

use r\Datum\Datum;
use r\Exceptions\RqlDriverError;

class BoolDatum extends Datum
{
    public function encodeServerRequest()
    {
        return (bool)$this->getValue();
    }

    public static function decodeServerResponse($json)
    {
        $result = new BoolDatum();
        $result->setValue((bool)$json);
        return $result;
    }

    public function __toString()
    {
        if ($this->getValue()) {
            return "true";
        } else {
            return "false";
        }
    }

    public function setValue($val)
    {
        if (is_numeric($val)) {
            $val = (($val == 0) ? false : true);
        }
        if (!is_bool($val)) {
            throw new RqlDriverError("Not a boolean: " . $val);
        }
        parent::setValue($val);
    }
}
