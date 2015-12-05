<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class InTimezone extends ValuedQuery
{
    public function __construct(ValuedQuery $time, $timezone)
    {
        $timezone = $this->nativeToDatum($timezone);

        $this->setPositionalArg(0, $time);
        $this->setPositionalArg(1, $timezone);
    }

    protected function getTermType()
    {
        return TermTermType::PB_IN_TIMEZONE;
    }
}
