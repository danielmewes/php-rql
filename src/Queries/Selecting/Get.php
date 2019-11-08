<?php

namespace r\Queries\Selecting;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class Get extends ValuedQuery
{
    public function __construct(Table $table, $key)
    {
        $key = $this->nativeToDatum($key);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_GET;
    }
}
