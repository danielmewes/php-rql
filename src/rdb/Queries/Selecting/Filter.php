<?php

namespace r\Queries\Selecting;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Filter extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $predicate, $default = null)
    {
        $predicate = \r\nativeToDatumOrFunction($predicate);
        if (isset($default)) {
            $default = \r\nativeToDatum($default);
        }

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $predicate);
        if (isset($default)) {
            $this->setOptionalArg('default', $default);
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_FILTER;
    }
}
