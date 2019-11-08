<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Args extends ValuedQuery
{
    public function __construct($args)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($args));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_ARGS;
    }
}
