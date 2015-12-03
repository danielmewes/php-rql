<?php

namespace r\Queries\Selecting;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Get extends ValuedQuery
{
    public function __construct(Table $table, $key)
    {
        $key = $this->nativeToDatum($key);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
    }

    protected function getTermType()
    {
        return TermTermType::PB_GET;
    }
}
