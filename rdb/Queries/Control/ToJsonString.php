<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class ToJsonString extends ValuedQuery
{
    public function __construct(ValuedQuery $val)
    {
        $this->setPositionalArg(0, $val);
    }

    protected function getTermType()
    {
        return TermTermType::PB_TO_JSON_STRING;
    }
}
