<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class InTimezone extends ValuedQuery
{
    public function __construct(ValuedQuery $time, $timezone)
    {
        $timezone = $this->nativeToDatum($timezone);

        $this->setPositionalArg(0, $time);
        $this->setPositionalArg(1, $timezone);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_IN_TIMEZONE;
    }
}
