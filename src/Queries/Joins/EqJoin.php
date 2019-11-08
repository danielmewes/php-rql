<?php

namespace r\Queries\Joins;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class EqJoin extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attribute, ValuedQuery $otherSequence, array $opts = [])
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatumOrFunction($attribute));
        $this->setPositionalArg(2, $otherSequence);

        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_EQ_JOIN;
    }
}
