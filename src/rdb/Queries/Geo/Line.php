<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class Line extends ValuedQuery
{
    public function __construct($points)
    {
        if (!is_array($points)) {
            throw new RqlDriverError("Points must be an array.");
        }
        $i = 0;
        foreach ($points as $point) {
            $this->setPositionalArg($i++, \r\nativeToDatum($point));
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_LINE;
    }
}
