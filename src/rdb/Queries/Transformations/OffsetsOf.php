<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class OffsetsOf extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $predicate)
    {
        $predicate = \r\nativeToDatumOrFunction($predicate);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $predicate);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_OFFSETS_OF;
    }
}
