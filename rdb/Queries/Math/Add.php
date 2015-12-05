<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Add extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_ADD, $value, $other);
    }
}
