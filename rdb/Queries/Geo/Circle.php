<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class Circle extends ValuedQuery
{
    public function __construct($center, $radius, $opts)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($center));
        $this->setPositionalArg(1, $this->nativeToDatum($radius));
        if (isset($opts)) {
            if (!is_array($opts)) {
                throw new RqlDriverError("opts argument must be an array");
            }
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, $this->nativeToDatum($v));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_CIRCLE;
    }
}
