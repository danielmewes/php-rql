<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Ceil extends ValuedQuery
{
    public function __construct($value)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($value));
    }

    protected function getTermType()
    {
        return Term_TermType::PB_CEIL;
    }
}
