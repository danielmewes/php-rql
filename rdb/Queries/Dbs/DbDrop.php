<?php

namespace r\Queries\Dbs;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class DbDrop extends ValuedQuery
{
    public function __construct($dbName)
    {
        $dbName = $this->nativeToDatum($dbName);
        $this->setPositionalArg(0, $dbName);
    }

    protected function getTermType()
    {
        return TermTermType::PB_DB_DROP;
    }
}
