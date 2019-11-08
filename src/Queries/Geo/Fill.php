<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Fill extends ValuedQuery
{
    public function __construct($g1)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($g1));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_FILL;
    }
}
