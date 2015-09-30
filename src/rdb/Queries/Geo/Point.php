<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Point extends ValuedQuery
{
    public function __construct($lat, $lon)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($lat));
        $this->setPositionalArg(1, \r\nativeToDatum($lon));
    }

    protected function getTermType()
    {
        return Term_TermType::PB_POINT;
    }
}
