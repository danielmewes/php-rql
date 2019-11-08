<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Upcase extends ValuedQuery
{
    public function __construct(ValuedQuery $value)
    {
        $this->setPositionalArg(0, $value);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_UPCASE;
    }
}
