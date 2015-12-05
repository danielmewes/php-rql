<?php

namespace r\Queries\Selecting;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Filter extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $predicate, $default = null)
    {
        $predicate = $this->nativeToDatumOrFunction($predicate);
        if (isset($default)) {
            $default = $this->nativeToDatum($default);
        }

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $predicate);
        if (isset($default)) {
            $this->setOptionalArg('default', $default);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_FILTER;
    }
}
