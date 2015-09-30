<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class TypeOf extends ValuedQuery
{
    public function __construct(ValuedQuery $value)
    {
        $this->setPositionalArg(0, $value);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_TYPE_OF;
    }
}
