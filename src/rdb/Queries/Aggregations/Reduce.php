<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Reduce extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $reductionFunction)
    {
        $reductionFunction = \r\nativeToFunction($reductionFunction);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $reductionFunction);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_REDUCE;
    }
}
