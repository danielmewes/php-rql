<?php

namespace r\Queries\Transformations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Nth extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index)
    {
        $index = $this->nativeToDatum($index);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_NTH;
    }
}
