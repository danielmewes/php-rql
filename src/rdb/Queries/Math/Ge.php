<?php

namespace r\Queries\Math;

use r\pb\Term_TermType;

class Ge extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(Term_TermType::PB_GE, $value, $other);
    }
}
