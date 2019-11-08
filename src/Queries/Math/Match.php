<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Match extends ValuedQuery
{
    public function __construct(ValuedQuery $value, $expression)
    {
        $expression = $this->nativeToDatum($expression);

        $this->setPositionalArg(0, $value);
        $this->setPositionalArg(1, $expression);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_MATCH;
    }
}
