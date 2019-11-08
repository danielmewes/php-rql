<?php

namespace r\Queries\Index;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class IndexList extends ValuedQuery
{
    public function __construct(Table $table)
    {
        $this->setPositionalArg(0, $table);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_INDEX_LIST;
    }
}
