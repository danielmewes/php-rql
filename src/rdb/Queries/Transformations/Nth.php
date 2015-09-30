<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Nth extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index)
    {
        $index = \r\nativeToDatum($index);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_NTH;
    }
}
