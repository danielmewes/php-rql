<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Wednesday extends ValuedQuery
{
    protected function getTermType(): int
    {
        return TermTermType::PB_WEDNESDAY;
    }
}
