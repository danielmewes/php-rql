<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;

class Div extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_DIV, $value, $other);
    }
}
