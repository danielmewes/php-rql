<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Distance extends ValuedQuery
{
    public function __construct($g1, $g2, array $opts = [])
    {
        $this->setPositionalArg(0, $this->nativeToDatum($g1));
        $this->setPositionalArg(1, $this->nativeToDatum($g2));
        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_DISTANCE;
    }
}
