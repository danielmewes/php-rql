<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class June extends ValuedQuery
{
    protected function getTermType(): int
    {
        return TermTermType::PB_JUNE;
    }
}
