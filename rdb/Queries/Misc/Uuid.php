<?php

namespace r\Queries\Misc;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Uuid extends ValuedQuery
{
    public function __construct($str = null)
    {
        if (isset($str)) {
            $this->setPositionalArg(0, $this->nativeToDatum($str));
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_UUID;
    }
}
