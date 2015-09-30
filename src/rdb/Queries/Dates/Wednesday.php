<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Wednesday extends ValuedQuery
{
    protected function getTermType()
    {
        return Term_TermType::PB_WEDNESDAY;
    }
}
