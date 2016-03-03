<?php

namespace r\ValuedQuery;

use r\Exceptions\RqlDriverError;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class MakeArray extends ValuedQuery
{
    public function __construct($value)
    {
        if (!is_array($value)) {
            throw new RqlDriverError("Value must be an array.");
        }
        $i = 0;
        foreach ($value as $val) {
            $this->setPositionalArg($i++, $val);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_MAKE_ARRAY;
    }
}
