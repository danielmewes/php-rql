<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

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

    protected function getTermType()
    {
        return TermTermType::PB_SPLIT;
    }
}
