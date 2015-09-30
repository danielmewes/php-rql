<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class InTimezone extends ValuedQuery
{
    public function __construct(ValuedQuery $time, $timezone)
    {
        $timezone = \r\nativeToDatum($timezone);

        $this->setPositionalArg(0, $time);
        $this->setPositionalArg(1, $timezone);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_IN_TIMEZONE;
    }
}
