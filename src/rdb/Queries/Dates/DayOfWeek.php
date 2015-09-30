<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class DayOfWeek extends ValuedQuery
{
    public function __construct(ValuedQuery $time)
    {
        $this->setPositionalArg(0, $time);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_DAY_OF_WEEK;
    }
}
