<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Point extends ValuedQuery
{
    public function __construct($lat, $lon)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($lat));
        $this->setPositionalArg(1, $this->nativeToDatum($lon));
    }

    protected function getTermType()
    {
        return TermTermType::PB_POINT;
    }
}
