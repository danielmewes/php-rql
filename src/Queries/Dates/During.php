<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class During extends ValuedQuery
{
    public function __construct(ValuedQuery $time, $startTime, $endTime, array $opts = [])
    {
        $this->setPositionalArg(0, $time);
        $this->setPositionalArg(1, $this->nativeToDatum($startTime));
        $this->setPositionalArg(2, $this->nativeToDatum($endTime));

        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_DURING;
    }
}
