<?php

namespace r\Queries\Selecting;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class GetMultiple extends ValuedQuery
{
    public function __construct(Table $table, $keys, $opts = null)
    {
        if (!is_array($keys)) {
            throw new RqlDriverError("Keys in GetMultiple must be an array.");
        }
        foreach ($keys as &$key) {
            $key = $this->nativeToDatum($key);
            unset($key);
        }
        $this->setPositionalArg(0, $table);
        $i = 1;
        foreach ($keys as $key) {
            $this->setPositionalArg($i++, $key);
        }
        if (isset($opts)) {
            if (!is_array($opts)) {
                throw new RqlDriverError("opts argument must be an array");
            }
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, $this->nativeToDatum($v));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_GET_ALL;
    }
}
