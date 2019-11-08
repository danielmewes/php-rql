<?php

namespace r\Queries\Manipulation;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class DeleteAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $endIndex = null)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatum($index));
        if (isset($endIndex)) {
            $this->setPositionalArg(2, $this->nativeToDatum($endIndex));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_DELETE_AT;
    }
}
