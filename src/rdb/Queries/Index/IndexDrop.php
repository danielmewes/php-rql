<?php

namespace r\Queries\Index;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class IndexDrop extends ValuedQuery
{
    public function __construct(Table $table, $indexName)
    {
        $indexName = \r\nativeToDatum($indexName);
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $indexName);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_INDEX_DROP;
    }
}
