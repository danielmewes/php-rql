<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;

class Mul extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_MUL, $value, $other);
    }
}
