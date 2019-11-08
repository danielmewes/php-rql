<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class RForeach extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $queryFunction)
    {
        $queryFunction = $this->nativeToFunction($queryFunction);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $queryFunction);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_FOR_EACH;
    }
}
