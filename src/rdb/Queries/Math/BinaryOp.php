<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;

class BinaryOp extends ValuedQuery
{
    public function __construct($termType, $value, $other)
    {
        $this->termType = $termType;

        $this->setPositionalArg(0, \r\nativeToDatum($value));
        $this->setPositionalArg(1, \r\nativeToDatum($other));
    }

    protected function getTermType()
    {
        return $this->termType;
    }

    private $termType;
}
