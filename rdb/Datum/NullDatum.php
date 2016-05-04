<?php

namespace r\Datum;

use r\Datum\Datum;
use r\Exceptions\RqlDriverError;

class NullDatum extends Datum
{
    public function encodeServerRequest()
    {
        return null;
    }

    public static function decodeServerResponse($json)
    {
        $result = new NullDatum();
        $result->setValue(null);
        return $result;
    }

    public function setValue($val)
    {
        if (!is_null($val)) {
            throw new RqlDriverError("Not null: " . $val);
        }
        parent::setValue($val);
    }

    public function __toString()
    {
        return "null";
    }
}
