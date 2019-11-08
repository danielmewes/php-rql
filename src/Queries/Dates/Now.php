<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Now extends ValuedQuery
{
    public function __construct()
    {
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_NOW;
    }
}
