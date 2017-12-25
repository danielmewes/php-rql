<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;

class BinaryOp extends ValuedQuery
{
    public function __construct($termType, array $exprs)
    {
        $this->termType = $termType;

        foreach ($exprs as $k => $expr) {
            $this->setPositionalArg($k, $this->nativeToDatum($expr));
        }
    }

    protected function getTermType()
    {
        return $this->termType;
    }

    private $termType;
}
