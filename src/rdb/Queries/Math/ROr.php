<?php

namespace r\Queries\Math;

use r\pb\Term_TermType;

class ROr extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(Term_TermType::PB_OR, $value, $other);
    }
}
