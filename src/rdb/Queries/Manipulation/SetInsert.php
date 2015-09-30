<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class SetInsert extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $value)
    {
        $value = \r\nativeToDatum($value);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $value);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_SET_INSERT;
    }
}
