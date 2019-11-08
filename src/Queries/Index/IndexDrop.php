<?php

namespace r\Queries\Index;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class IndexDrop extends ValuedQuery
{
    public function __construct(Table $table, $indexName)
    {
        $indexName = $this->nativeToDatum($indexName);
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $indexName);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_INDEX_DROP;
    }
}
