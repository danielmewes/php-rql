<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class February extends ValuedQuery
{
    protected function getTermType()
    {
        return TermTermType::PB_FEBRUARY;
    }
}
