<?php

namespace r\Queries\Tables;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class TableList extends ValuedQuery
{
    public function __construct($database)
    {
        if (isset($database) && !is_a($database, '\r\Queries\Dbs\Db')) {
            throw new RqlDriverError("Database is not a Db object.");
        }
        if (isset($database)) {
            $this->setPositionalArg(0, $database);
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_TABLE_LIST;
    }
}
