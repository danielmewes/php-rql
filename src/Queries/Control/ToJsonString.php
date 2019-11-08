<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class ToJsonString extends ValuedQuery
{
    public function __construct(ValuedQuery $val)
    {
        $this->setPositionalArg(0, $val);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_TO_JSON_STRING;
    }
}
