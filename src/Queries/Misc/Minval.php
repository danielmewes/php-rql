<?php

namespace r\Queries\Misc;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Minval extends ValuedQuery
{
    protected function getTermType(): int
    {
        return TermTermType::PB_MINVAL;
    }
}
