<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class Circle extends ValuedQuery
{
    public function __construct($center, $radius, $opts)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($center));
        $this->setPositionalArg(1, \r\nativeToDatum($radius));
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
        return Term_TermType::PB_CIRCLE;
    }
}
