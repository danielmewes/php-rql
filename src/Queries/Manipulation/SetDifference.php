<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class SetDifference extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value)
    {
        $value = $this->nativeToDatum($value);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }

    protected function getTermType()
    {
        return TermTermType::PB_SET_DIFFERENCE;
    }
}
