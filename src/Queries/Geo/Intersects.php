<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Intersects extends ValuedQuery
{
    public function __construct($g1, $g2)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($g1));
        $this->setPositionalArg(1, $this->nativeToDatum($g2));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_INTERSECTS;
    }
}
