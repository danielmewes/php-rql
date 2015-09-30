<?php

namespace r\Queries\Tables;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Status extends ValuedQuery
{
    public function __construct(Table $table)
    {
        $this->setPositionalArg(0, $table);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_STATUS;
    }
}
