<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Union extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $otherSequence);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_UNION;
    }
}
