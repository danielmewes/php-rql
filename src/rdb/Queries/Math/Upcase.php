<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Upcase extends ValuedQuery
{
    public function __construct(ValuedQuery $value)
    {
        $this->setPositionalArg(0, $value);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_UPCASE;
    }
}
