<?php

namespace r\Queries\Manipulation;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class HasFields extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ...$attributes)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatum($attributes));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_HAS_FIELDS;
    }
}
