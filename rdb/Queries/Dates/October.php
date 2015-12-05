<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class October extends ValuedQuery
{
    protected function getTermType()
    {
        return TermTermType::PB_OCTOBER;
    }
}
