<?php

namespace r\Queries\Geo;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class GetNearest extends ValuedQuery
{
    public function __construct(Table $table, $center, $opts = null)
    {
        $center = \r\nativeToDatum($center);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $center);
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
        return Term_TermType::PB_GET_NEAREST;
    }
}
