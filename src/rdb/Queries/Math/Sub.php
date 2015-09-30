<?php

namespace r\Queries\Math;

use r\pb\Term_TermType;

class Sub extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(Term_TermType::PB_SUB, $value, $other);
    }
}
