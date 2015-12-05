<?php

namespace r\Queries\Index;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class IndexList extends ValuedQuery
{
    public function __construct(Table $table)
    {
        $this->setPositionalArg(0, $table);
    }

    protected function getTermType()
    {
        return TermTermType::PB_INDEX_LIST;
    }
}
