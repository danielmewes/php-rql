<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Sample extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $n)
    {
        $n = \r\nativeToDatum($n);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $n);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_SAMPLE;
    }
}
