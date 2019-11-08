<?php

namespace r\Queries\Control;

use r\Datum\StringDatum;
use r\ProtocolBuffer\TermTermType;
use r\Query;
use r\ValuedQuery\ValuedQuery;

class CoerceTo extends ValuedQuery
{
    public function __construct(ValuedQuery $value, $typeName)
    {
        if (!$typeName instanceof Query) {
            $typeName = new StringDatum($typeName);
        }

        $this->setPositionalArg(0, $value);
        $this->setPositionalArg(1, $typeName);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_COERCE_TO;
    }
}
