<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Point extends ValuedQuery
{
    public function __construct($lat, $lon)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($lat));
        $this->setPositionalArg(1, $this->nativeToDatum($lon));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_POINT;
    }
}
