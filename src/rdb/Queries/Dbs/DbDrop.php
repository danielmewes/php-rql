<?php

namespace r\Queries\Dbs;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class DbDrop extends ValuedQuery
{
    public function __construct($dbName)
    {
        $dbName = \r\nativeToDatum($dbName);
        $this->setPositionalArg(0, $dbName);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_DB_DROP;
    }
}
