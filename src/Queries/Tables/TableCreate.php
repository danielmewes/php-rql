<?php

namespace r\Queries\Tables;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Dbs\Db;
use r\ValuedQuery\ValuedQuery;

class TableCreate extends ValuedQuery
{
    public function __construct(?Db $database, $tableName, array $options = [])
    {
        $i = 0;
        if (isset($database)) {
            $this->setPositionalArg($i++, $database);
        }
        $this->setPositionalArg($i++, $this->nativeToDatum($tableName));
        foreach ($options as $key => $val) {
            $this->setOptionalArg($key, $this->nativeToDatum($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_TABLE_CREATE;
    }
}
