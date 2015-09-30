<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Range extends ValuedQuery
{
    public function __construct($startOrEndValue = null, $endValue = null)
    {
        if (isset($startOrEndValue)) {
            $this->setPositionalArg(0, \r\nativeToDatum($startOrEndValue));
            if (isset($endValue)) {
                $this->setPositionalArg(1, \r\nativeToDatum($endValue));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_RANGE;
    }
}
