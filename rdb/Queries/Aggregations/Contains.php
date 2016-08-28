<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Contains extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, array $values)
    {
        $this->setPositionalArg(0, $sequence);

        foreach ($values as $k => $value) {
            $this->setPositionalArg($k+1, $this->nativeToDatumOrFunction($value));
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_CONTAINS;
    }
}
