<?php

namespace r\Queries\Joins;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class OuterJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate)
    {
        $predicate = $this->nativeToFunction($predicate);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $otherSequence);
        $this->setPositionalArg(2, $predicate);
    }

    protected function getTermType()
    {
        return TermTermType::PB_OUTER_JOIN;
    }
}
