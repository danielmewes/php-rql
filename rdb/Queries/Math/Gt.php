<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Gt extends BinaryOp
{
    public function __construct($value, $other)
    {
        parent::__construct(TermTermType::PB_GT, $value, $other);
    }
}
