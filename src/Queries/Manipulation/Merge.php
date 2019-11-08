<?php

namespace r\Queries\Manipulation;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Merge extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $other)
    {
        $other = $this->nativeToDatumOrFunction($other);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $other);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_MERGE;
    }
}
