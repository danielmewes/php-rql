<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Circle extends ValuedQuery
{
    public function __construct($center, $radius, array $opts = [])
    {
        $this->setPositionalArg(0, $this->nativeToDatum($center));
        $this->setPositionalArg(1, $this->nativeToDatum($radius));
        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_CIRCLE;
    }
}
