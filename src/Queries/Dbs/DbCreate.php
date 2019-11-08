<?php

namespace r\Queries\Dbs;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class DbCreate extends ValuedQuery
{
    public function __construct($dbName)
    {
        $dbName = $this->nativeToDatum($dbName);
        $this->setPositionalArg(0, $dbName);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_DB_CREATE;
    }
}
