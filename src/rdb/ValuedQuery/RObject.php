<?php

namespace r\ValuedQuery;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class RObject extends ValuedQuery
{
    public function __construct($object)
    {
        if (!is_array($object)) {
            throw RqlDriverError("Argument to r\\Object must be an array.");
        }
        $i = 0;
        foreach ($object as $v) {
            $this->setPositionalArg($i++, \r\nativeToDatum($v));
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_OBJECT;
    }
}
