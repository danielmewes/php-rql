<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Split extends ValuedQuery
{
    public function __construct(ValuedQuery $value, $separator = null, $maxSplits = null)
    {
        $this->setPositionalArg(0, $value);
        if (isset($separator) || isset($maxSplits)) {
            $this->setPositionalArg(1, \r\nativeToDatum($separator));
        }
        if (isset($maxSplits)) {
            $this->setPositionalArg(2, \r\nativeToDatum($maxSplits));
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_SPLIT;
    }
}
