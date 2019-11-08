<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class PolygonSub extends ValuedQuery
{
    public function __construct($p1, $p2)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($p1));
        $this->setPositionalArg(1, $this->nativeToDatum($p2));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_POLYGON_SUB;
    }
}
