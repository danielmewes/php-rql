<?php

namespace r\Queries\Index;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class IndexDrop extends ValuedQuery
{
    public function __construct(Table $table, $indexName)
    {
        $indexName = $this->nativeToDatum($indexName);
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $indexName);
    }

    protected function getTermType()
    {
        return TermTermType::PB_INDEX_DROP;
    }
}
