<?php

namespace r\Queries\Aggregations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Sum extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute = null)
    {
        $this->setPositionalArg(0, $sequence);
        if (null !== $attribute) {
            $this->setPositionalArg(1, $this->nativeToDatumOrFunction($attribute));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_SUM;
    }
}
