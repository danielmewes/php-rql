<?php

namespace r\Datum;

use r\Datum\Datum;

class NumberDatum extends Datum
{
    public function _getJSONTerm()
    {
        return (float)$this->getValue();
    }

    public static function _fromJSON($json)
    {
        $result = new NumberDatum();
        $result->setValue((float)$json);
        return $result;
    }

    public function setValue($val)
    {
        if (!is_numeric($val)) {
            throw new RqlDriverError("Not a number: " . $val);
        }
        parent::setValue($val);
    }
}
