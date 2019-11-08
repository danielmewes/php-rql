<?php

namespace r\Queries\Manipulation;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class SetInsert extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value)
    {
        $value = $this->nativeToDatum($value);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_SET_INSERT;
    }
}
