<?php

namespace r\Queries\Joins;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Zip extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence)
    {
        $this->setPositionalArg(0, $sequence);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_ZIP;
    }
}
