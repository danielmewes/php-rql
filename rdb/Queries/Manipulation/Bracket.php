<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Bracket extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributeOrIndex)
    {
        if (!(is_object($attributeOrIndex) && is_subclass_of($attributeOrIndex, "\\r\\Query"))) {
            $attributeOrIndex = $this->nativeToDatum($attributeOrIndex);
        }

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributeOrIndex);
    }

    protected function getTermType()
    {
        return TermTermType::PB_BRACKET;
    }
}
