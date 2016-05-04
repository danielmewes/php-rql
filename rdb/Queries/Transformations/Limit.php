<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Limit extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $n)
    {
        $n = $this->nativeToDatum($n);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $n);
    }

    protected function getTermType()
    {
        return TermTermType::PB_LIMIT;
    }
}
