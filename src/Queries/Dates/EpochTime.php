<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class EpochTime extends ValuedQuery
{
    public function __construct($epochTime)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($epochTime));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_EPOCH_TIME;
    }
}
