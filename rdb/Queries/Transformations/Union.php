<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Union extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $otherSequence);
    }

    protected function getTermType()
    {
        return TermTermType::PB_UNION;
    }
}
