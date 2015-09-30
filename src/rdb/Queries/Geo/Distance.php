<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class Distance extends ValuedQuery
{
    public function __construct($g1, $g2, $opts = null)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($g1));
        $this->setPositionalArg(1, \r\nativeToDatum($g2));
        if (isset($opts)) {
            if (!is_array($opts)) {
                throw new RqlDriverError("opts argument must be an array");
            }
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, \r\nativeToDatum($v));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_DISTANCE;
    }
}
