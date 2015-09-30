<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Contains extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value)
    {
        $value = \r\nativeToDatumOrFunction($value);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_CONTAINS;
    }
}
