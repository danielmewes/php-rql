<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Ne extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_NE, $value, $other);
    }
}
