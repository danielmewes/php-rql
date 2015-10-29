<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

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

    protected function getTermType()
    {
        return TermTermType::PB_RANGE;
    }
}
