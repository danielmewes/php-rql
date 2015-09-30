<?php

namespace r\Queries\Selecting;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class GetAll extends ValuedQuery
{
    public function __construct(Table $table, $key, $opts = null)
    {
        $key = \r\nativeToDatum($key);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
        if (isset($opts)) {
            if (!is_array($opts)) {
                throw new RqlDriverError("opts argument must be an array");
            }
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, \r\nativeToDatum($v));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_GET_ALL;
    }
}
