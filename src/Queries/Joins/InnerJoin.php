<?php

namespace r\Queries\Joins;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class InnerJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $otherSequence);
        $this->setPositionalArg(2, $this->nativeToFunction($predicate));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_INNER_JOIN;
    }
}
