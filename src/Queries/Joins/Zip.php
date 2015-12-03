<?php

namespace r\Queries\Joins;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Zip extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence)
    {
        $this->setPositionalArg(0, $sequence);
    }

    protected function getTermType()
    {
        return TermTermType::PB_ZIP;
    }
}
