<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Reduce extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $reductionFunction)
    {
        $reductionFunction = $this->nativeToFunction($reductionFunction);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $reductionFunction);
    }

    protected function getTermType()
    {
        return TermTermType::PB_REDUCE;
    }
}
