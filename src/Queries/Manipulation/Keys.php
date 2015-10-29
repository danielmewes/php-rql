<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Keys extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence)
    {
        $this->setPositionalArg(0, $sequence);
    }

    protected function getTermType()
    {
        return TermTermType::PB_KEYS;
    }
}
