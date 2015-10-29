<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;

class Le extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_LE, $value, $other);
    }
}
