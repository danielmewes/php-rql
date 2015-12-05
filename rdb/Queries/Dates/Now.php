<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Now extends ValuedQuery
{
    public function __construct()
    {
    }

    protected function getTermType()
    {
        return TermTermType::PB_NOW;
    }
}
