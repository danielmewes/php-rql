<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Fill extends ValuedQuery
{
    public function __construct($g1)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($g1));
    }

    protected function getTermType()
    {
        return Term_TermType::PB_FILL;
    }
}
