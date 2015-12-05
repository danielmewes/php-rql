<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class DeleteAt extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $index, $endIndex = null)
    {
        $index = $this->nativeToDatum($index);
        if (isset($endIndex)) {
            $endIndex = $this->nativeToDatum($endIndex);
        }


        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $index);
        if (isset($endIndex)) {
            $this->setPositionalArg(2, $endIndex);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_DELETE_AT;
    }
}
