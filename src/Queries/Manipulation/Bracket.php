<?php

namespace r\Queries\Manipulation;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Bracket extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributeOrIndex)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatum($attributeOrIndex));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_BRACKET;
    }
}
