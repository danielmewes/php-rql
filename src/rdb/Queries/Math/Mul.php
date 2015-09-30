<?php

namespace r\Queries\Math;

use r\pb\Term_TermType;

class Mul extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(Term_TermType::PB_MUL, $value, $other);
    }
}
