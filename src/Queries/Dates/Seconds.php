<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Seconds extends ValuedQuery
{
    public function __construct(ValuedQuery $time)
    {
        $this->setPositionalArg(0, $time);
    }

    protected function getTermType()
    {
        return TermTermType::PB_SECONDS;
    }
}
