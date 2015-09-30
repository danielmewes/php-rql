<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class ToJsonString extends ValuedQuery
{
    public function __construct(ValuedQuery $val)
    {
        $this->setPositionalArg(0, $val);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_TO_JSON_STRING;
    }
}
