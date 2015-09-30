<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Merge extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $other)
    {
        $other = \r\nativeToDatumOrFunction($other);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $other);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_MERGE;
    }
}
