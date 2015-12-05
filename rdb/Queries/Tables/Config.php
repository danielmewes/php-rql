<?php

namespace r\Queries\Tables;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Config extends ValuedQuery
{
    public function __construct(Table $table)
    {
        $this->setPositionalArg(0, $table);
    }

    protected function getTermType()
    {
        return TermTermType::PB_CONFIG;
    }
}
