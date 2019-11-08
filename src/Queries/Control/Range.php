<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Range extends ValuedQuery
{
    public function __construct($startOrEndValue = null, $endValue = null)
    {
        if (isset($startOrEndValue)) {
            $this->setPositionalArg(0, $this->nativeToDatum($startOrEndValue));
            if (isset($endValue)) {
                $this->setPositionalArg(1, $this->nativeToDatum($endValue));
            }
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_RANGE;
    }
}
