<?php

namespace r\Queries\Tables;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Dbs\Db;
use r\ValuedQuery\ValuedQuery;

class TableDrop extends ValuedQuery
{
    public function __construct(?Db $database, $tableName)
    {
        $i = 0;
        if (isset($database)) {
            $this->setPositionalArg($i++, $database);
        }
        $this->setPositionalArg($i++, $this->nativeToDatum($tableName));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_TABLE_DROP;
    }
}
