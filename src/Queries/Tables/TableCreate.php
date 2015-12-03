<?php

namespace r\Queries\Tables;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class TableCreate extends ValuedQuery
{
    public function __construct($database, $tableName, $options = null)
    {
        if (isset($database) && !is_a($database, 'r\Queries\Dbs\Db')) {
            throw new RqlDriverError("Database is not a Db object.");
        }
        $tableName = $this->nativeToDatum($tableName);
        if (isset($options)) {
            if (!is_array($options)) {
                throw new RqlDriverError("Options must be an array.");
            }
            foreach ($options as $key => &$val) {
                if (!is_string($key)) {
                    throw new RqlDriverError("Option keys must be strings.");
                }
                if (!(is_object($val) && is_subclass_of($val, "\\r\\Query"))) {
                    $val = $this->nativeToDatum($val);
                }
                unset($val);
            }
        }

        $i = 0;
        if (isset($database)) {
            $this->setPositionalArg($i++, $database);
        }
        $this->setPositionalArg($i++, $tableName);
        if (isset($options)) {
            foreach ($options as $key => $val) {
                $this->setOptionalArg($key, $val);
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_TABLE_CREATE;
    }
}
