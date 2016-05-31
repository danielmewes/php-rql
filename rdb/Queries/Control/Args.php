<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Args extends ValuedQuery
{
    public function __construct($args)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($args));
    }

    protected function getTermType()
    {
        return TermTermType::PB_ARGS;
    }
}
