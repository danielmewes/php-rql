<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class DeleteAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $endIndex = null)
    {
        $index = \r\nativeToDatum($index);
        if (isset($endIndex)) {
            $endIndex = \r\nativeToDatum($endIndex);
        }


        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
        if (isset($endIndex)) {
            $this->setPositionalArg(2, $endIndex);
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_DELETE_AT;
    }
}
