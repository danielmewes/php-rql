<?php

namespace r\Queries\Tables;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Status extends ValuedQuery
{
    public function __construct(Table $table)
    {
        $this->setPositionalArg(0, $table);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_STATUS;
    }
}
