<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Ceil extends ValuedQuery
{
    public function __construct($value)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($value));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_CEIL;
    }
}
