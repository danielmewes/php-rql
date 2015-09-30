<?php

namespace r\Queries\Math;

use r\pb\Term_TermType;

class Le extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(Term_TermType::PB_LE, $value, $other);
    }
}
