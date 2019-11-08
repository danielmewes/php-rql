<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;

class Sub extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_SUB, $value, $other);
    }
}
