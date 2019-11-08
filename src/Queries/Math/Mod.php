<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;

class Mod extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_MOD, $value, $other);
    }
}
