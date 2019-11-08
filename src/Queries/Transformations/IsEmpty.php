<?php

namespace r\Queries\Transformations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class IsEmpty extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence)
    {
        $this->setPositionalArg(0, $sequence);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_IS_EMPTY;
    }
}
