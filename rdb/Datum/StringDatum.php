<?php

namespace r\Datum;

use r\Datum\Datum;
use r\Exceptions\RqlDriverError;

class StringDatum extends Datum
{
    public function encodeServerRequest()
    {
        return (string)$this->getValue();
    }

    public static function decodeServerResponse($json)
    {
        $result = new StringDatum();
        $result->setValue((string)$json);
        return $result;
    }

    public function setValue($val)
    {
        if (!is_string($val)) {
            throw new RqlDriverError("Not a string");
        }
        parent::setValue($val);
    }

    public function __toString()
    {
        return "'" . $this->getValue() . "'";
    }
}
