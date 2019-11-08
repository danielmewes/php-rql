<?php

namespace r\Queries\Aggregations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Count extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $filter = null)
    {
        $this->setPositionalArg(0, $sequence);
        if (null !== $filter) {
            $this->setPositionalArg(1, $this->nativeToDatumOrFunction($filter));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_COUNT;
    }
}
