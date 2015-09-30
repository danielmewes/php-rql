<?php

namespace r\Queries\Joins;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class InnerJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ValuedQuery $otherSequence, $predicate)
    {
        $predicate = \r\nativeToFunction($predicate);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $otherSequence);
        $this->setPositionalArg(2, $predicate);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_INNER_JOIN;
    }
}
