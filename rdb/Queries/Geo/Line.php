<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class Line extends ValuedQuery
{
    public function __construct($points)
    {
        if (!is_array($points)) {
            throw new RqlDriverError("Points must be an array.");
        }
        $i = 0;
        foreach ($points as $point) {
            $this->setPositionalArg($i++, $this->nativeToDatum($point));
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_LINE;
    }
}
