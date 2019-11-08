<?php

namespace r\Queries\Tables;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Dbs\Db;
use r\ValuedQuery\ValuedQuery;

class TableList extends ValuedQuery
{
    public function __construct(?Db $database = null)
    {
        if (isset($database)) {
            $this->setPositionalArg(0, $database);
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_TABLE_LIST;
    }
}
