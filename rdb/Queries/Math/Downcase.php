<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Downcase extends ValuedQuery
{
    public function __construct(ValuedQuery $value)
    {
        $this->setPositionalArg(0, $value);
    }

    protected function getTermType()
    {
        return TermTermType::PB_DOWNCASE;
    }
}
