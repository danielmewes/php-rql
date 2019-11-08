<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class February extends ValuedQuery
{
    protected function getTermType(): int
    {
        return TermTermType::PB_FEBRUARY;
    }
}
