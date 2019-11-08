<?php

namespace r\Queries\Selecting;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Between extends ValuedQuery
{
    public function __construct(ValuedQuery $selection, $leftBound, $rightBound, array $opts = [])
    {
        $leftBound = $this->nativeToDatum($leftBound);
        $rightBound = $this->nativeToDatum($rightBound);

        $this->setPositionalArg(0, $selection);
        $this->setPositionalArg(1, $leftBound);
        $this->setPositionalArg(2, $rightBound);
        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_BETWEEN;
    }
}
