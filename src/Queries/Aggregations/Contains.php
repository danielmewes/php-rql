<?php

namespace r\Queries\Aggregations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Contains extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatumOrFunction($value));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_CONTAINS;
    }
}
