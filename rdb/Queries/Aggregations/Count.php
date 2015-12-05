<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Count extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $filter = null)
    {
        if (isset($filter)) {
            $filter = $this->nativeToDatumOrFunction($filter);
        }

        $this->setPositionalArg(0, $sequence);
        if (isset($filter)) {
            $this->setPositionalArg(1, $filter);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_COUNT;
    }
}
