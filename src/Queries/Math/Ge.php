<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;

class Ge extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_GE, $value, $other);
    }
}
