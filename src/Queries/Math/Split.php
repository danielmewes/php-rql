<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Split extends ValuedQuery
{
    public function __construct(ValuedQuery $value, $separator = null, $maxSplits = null)
    {
        $this->setPositionalArg(0, $value);
        if (isset($separator) || isset($maxSplits)) {
            $this->setPositionalArg(1, $this->nativeToDatum($separator));
        }
        if (isset($maxSplits)) {
            $this->setPositionalArg(2, $this->nativeToDatum($maxSplits));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_SPLIT;
    }
}
