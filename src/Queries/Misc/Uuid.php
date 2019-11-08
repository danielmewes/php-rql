<?php

namespace r\Queries\Misc;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Uuid extends ValuedQuery
{
    public function __construct($str = null)
    {
        if (isset($str)) {
            $this->setPositionalArg(0, $this->nativeToDatum($str));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_UUID;
    }
}
