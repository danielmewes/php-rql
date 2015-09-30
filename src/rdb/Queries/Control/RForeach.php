<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class RForeach extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $queryFunction)
    {
        $queryFunction = \r\nativeToFunction($queryFunction);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $queryFunction);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_FOR_EACH;
    }
}
