<?php

namespace r\ValuedQuery;

use r\Exceptions\RqlDriverError;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class MakeObject extends ValuedQuery
{
    public function __construct($value)
    {
        if (!is_array($value)) {
            throw new RqlDriverError("Value must be an array.");
        }
        foreach ($value as $key => $val) {
            $this->setOptionalArg($key, $val);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_MAKE_OBJ;
    }
}
