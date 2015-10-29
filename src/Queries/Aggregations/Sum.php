<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Sum extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute = null)
    {
        if (isset($attribute)) {
            $attribute = $this->nativeToDatumOrFunction($attribute);
        }

        $this->setPositionalArg(0, $sequence);
        if (isset($attribute)) {
            $this->setPositionalArg(1, $attribute);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_SUM;
    }
}
