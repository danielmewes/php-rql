<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Polygon extends ValuedQuery
{
    public function __construct(array $points)
    {
        $i = 0;
        foreach ($points as $point) {
            $this->setPositionalArg($i++, $this->nativeToDatum($point));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_POLYGON;
    }
}
