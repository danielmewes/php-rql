<?php

namespace r\Queries\Manipulation;

use r\Datum\StringDatum;
use r\ProtocolBuffer\TermTermType;
use r\Query;
use r\ValuedQuery\ValuedQuery;

class GetField extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute)
    {
        if (!$attribute instanceof Query) {
            $attribute = new StringDatum($attribute);
        }

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attribute);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_GET_FIELD;
    }
}
