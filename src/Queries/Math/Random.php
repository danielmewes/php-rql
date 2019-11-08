<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Random extends ValuedQuery
{
    public function __construct($left = null, $right = null, array $opts = [])
    {
        if (isset($left)) {
            $this->setPositionalArg(0, $this->nativeToDatum($left));
        }
        if (isset($right)) {
            $this->setPositionalArg(1, $this->nativeToDatum($right));
        }
        foreach ($opts as $opt => $val) {
            $this->setOptionalArg($opt, $this->nativeToDatum($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_RANDOM;
    }
}
