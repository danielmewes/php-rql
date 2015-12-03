<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class RForeach extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $queryFunction)
    {
        $queryFunction = $this->nativeToFunction($queryFunction);
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $queryFunction);
    }

    protected function getTermType()
    {
        return TermTermType::PB_FOR_EACH;
    }
}
