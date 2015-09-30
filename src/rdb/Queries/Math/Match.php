<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Match extends ValuedQuery
{
    public function __construct(ValuedQuery $value, $expression)
    {
        $expression = \r\nativeToDatum($expression);

        $this->setPositionalArg(0, $value);
        $this->setPositionalArg(1, $expression);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_MATCH;
    }
}
