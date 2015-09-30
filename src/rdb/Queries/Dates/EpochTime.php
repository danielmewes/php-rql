<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class EpochTime extends ValuedQuery
{
    public function __construct($epochTime)
    {
        $epochTime = \r\nativeToDatum($epochTime);

        $this->setPositionalArg(0, $epochTime);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_EPOCH_TIME;
    }
}
