<?php

namespace r\Queries\Transformations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class OffsetsOf extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $predicate)
    {
        $predicate = $this->nativeToDatumOrFunction($predicate);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $predicate);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_OFFSETS_OF;
    }
}
