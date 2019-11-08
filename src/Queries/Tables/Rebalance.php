<?php

namespace r\Queries\Tables;

use r\ProtocolBuffer\TermTermType;
use r\Query;
use r\ValuedQuery\ValuedQuery;

class Rebalance extends ValuedQuery
{
    public function __construct(Query $tables)
    {
        $this->setPositionalArg(0, $tables);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_REBALANCE;
    }
}
