<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Ne extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(Term_TermType::PB_NE, $value, $other);
    }
}
