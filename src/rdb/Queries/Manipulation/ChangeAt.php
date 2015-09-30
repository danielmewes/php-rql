<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class ChangeAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $value)
    {
        $index = \r\nativeToDatum($index);
        $value = \r\nativeToDatum($value);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
        $this->setPositionalArg(2, $value);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_CHANGE_AT;
    }
}
