<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Merge extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $other)
    {
        $other = $this->nativeToDatumOrFunction($other);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $other);
    }

    protected function getTermType()
    {
        return TermTermType::PB_MERGE;
    }
}
