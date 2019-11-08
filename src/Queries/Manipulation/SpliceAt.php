<?php

namespace r\Queries\Manipulation;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class SpliceAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $value)
    {
        $index = $this->nativeToDatum($index);
        $value = $this->nativeToDatum($value);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
        $this->setPositionalArg(2, $value);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_SPLICE_AT;
    }
}
