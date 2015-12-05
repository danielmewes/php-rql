<?php

namespace r\Queries\Misc;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Maxval extends ValuedQuery
{
    protected function getTermType()
    {
        return TermTermType::PB_MAXVAL;
    }
}
