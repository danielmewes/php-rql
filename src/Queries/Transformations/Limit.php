<?php

namespace r\Queries\Transformations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Limit extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $n)
    {
        $n = $this->nativeToDatum($n);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $n);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_LIMIT;
    }
}
