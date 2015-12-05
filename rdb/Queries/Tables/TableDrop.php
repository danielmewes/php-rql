<?php

namespace r\Queries\Tables;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;
use r\Exceptions\RqlDriverError;

class TableDrop extends ValuedQuery
{
    public function __construct($database, $tableName)
    {
        if (isset($database) && !is_a($database, '\r\Queries\Dbs\Db')) {
            throw new RqlDriverError("Database is not a Db object.");
        }
        $tableName = $this->nativeToDatum($tableName);

        $i = 0;
        if (isset($database)) {
            $this->setPositionalArg($i++, $database);
        }
        $this->setPositionalArg($i++, $tableName);
    }

    protected function getTermType()
    {
        return TermTermType::PB_TABLE_DROP;
    }
}
