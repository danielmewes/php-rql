<?php

namespace r\Queries\Selecting;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Filter extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $predicate, $default = null)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatumOrFunction($predicate));
        if (isset($default)) {
            $this->setOptionalArg('default', $this->nativeToDatum($default));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_FILTER;
    }
}
