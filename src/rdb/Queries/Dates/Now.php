<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Now extends ValuedQuery
{
    public function __construct()
    {
    }

    protected function getTermType()
    {
        return Term_TermType::PB_NOW;
    }
}
