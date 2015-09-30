<?php

namespace r\Queries\Index;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class IndexList extends ValuedQuery
{
    public function __construct(Table $table)
    {
        $this->setPositionalArg(0, $table);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_INDEX_LIST;
    }
}
