<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class IsEmpty extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence)
    {
        $this->setPositionalArg(0, $sequence);
    }

    protected function getTermType()
    {
        return TermTermType::PB_IS_EMPTY;
    }
}
