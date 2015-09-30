<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Sum extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute = null)
    {
        if (isset($attribute)) {
            $attribute = \r\nativeToDatumOrFunction($attribute);
        }

        $this->setPositionalArg(0, $sequence);
        if (isset($attribute)) {
            $this->setPositionalArg(1, $attribute);
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_SUM;
    }
}
