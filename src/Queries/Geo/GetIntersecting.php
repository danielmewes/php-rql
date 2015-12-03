<?php

namespace r\Queries\Geo;

use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\ProtocolBuffer\TermTermType;

class GetIntersecting extends ValuedQuery
{
    public function __construct(Table $table, $geo, $opts = null)
    {
        $geo = $this->nativeToDatum($geo);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $geo);
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
        return TermTermType::PB_GET_INTERSECTING;
    }
}
