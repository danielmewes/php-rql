<?php

namespace r\Queries\Tables;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;
use r\Exceptions\RqlDriverError;

class TableDrop extends ValuedQuery
{
    public function __construct($database, $tableName)
    {
        if (isset($database) && !is_a($database, '\r\Queries\Dbs\Db')) {
            throw new RqlDriverError("Database is not a Db object.");
        }
        $tableName = \r\nativeToDatum($tableName);

        $i = 0;
        if (isset($database)) {
            $this->setPositionalArg($i++, $database);
        }
        $this->setPositionalArg($i++, $tableName);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_TABLE_DROP;
    }
}
