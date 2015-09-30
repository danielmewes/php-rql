<?php

namespace r\Queries\Selecting;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Get extends ValuedQuery
{
    public function __construct(Table $table, $key)
    {
        $key = \r\nativeToDatum($key);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_GET;
    }
}
