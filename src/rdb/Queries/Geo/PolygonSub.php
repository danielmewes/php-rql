<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class PolygonSub extends ValuedQuery
{
    public function __construct($p1, $p2)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($p1));
        $this->setPositionalArg(1, \r\nativeToDatum($p2));
    }

    protected function getTermType()
    {
        return Term_TermType::PB_POLYGON_SUB;
    }
}
