<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class EpochTime extends ValuedQuery
{
    public function __construct($epochTime)
    {
        $epochTime = $this->nativeToDatum($epochTime);

        $this->setPositionalArg(0, $epochTime);
    }

    protected function getTermType()
    {
        return TermTermType::PB_EPOCH_TIME;
    }
}
