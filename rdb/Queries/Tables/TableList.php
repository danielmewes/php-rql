<?php

namespace r\Queries\Tables;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

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
        return TermTermType::PB_TABLE_LIST;
    }
}
