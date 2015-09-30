<?php

namespace r\Queries\Tables;

use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Rebalance extends ValuedQuery
{
    public function __construct(Query $tables)
    {
        $this->setPositionalArg(0, $tables);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_REBALANCE;
    }
}
