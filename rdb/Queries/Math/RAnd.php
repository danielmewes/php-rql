<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;

class RAnd extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_AND, $value, $other);
    }
}
