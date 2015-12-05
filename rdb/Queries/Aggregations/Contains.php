<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Contains extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value)
    {
        $value = $this->nativeToDatumOrFunction($value);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }

    protected function getTermType()
    {
        return TermTermType::PB_CONTAINS;
    }
}
