<?php

namespace r\Queries\Math;

use r\ProtocolBuffer\TermTermType;

class ROr extends BinaryOp
{
    public function __construct(array $exprs)
    {
        parent::__construct(TermTermType::PB_OR, $exprs);
    }
}
