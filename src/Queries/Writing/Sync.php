<?php

namespace r\Queries\Writing;

use r\ValuedQuery\ValuedQuery;
use r\Queries\Tables\Table;
use r\ProtocolBuffer\TermTermType;

class Sync extends ValuedQuery
{
    public function __construct(Table $table)
    {
        $this->setPositionalArg(0, $table);
    }

    protected function getTermType()
    {
        return TermTermType::PB_SYNC;
    }
}
