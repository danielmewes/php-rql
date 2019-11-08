<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class TypeOf extends ValuedQuery
{
    public function __construct(ValuedQuery $value)
    {
        $this->setPositionalArg(0, $value);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_TYPE_OF;
    }
}
