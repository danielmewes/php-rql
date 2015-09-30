<?php

namespace r\ValuedQuery;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

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
        return Term_TermType::PB_MAKE_ARRAY;
    }
}
