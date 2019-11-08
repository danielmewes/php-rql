<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class GetIntersecting extends ValuedQuery
{
    public function __construct(Table $table, $geo, array $opts = [])
    {
        $geo = $this->nativeToDatum($geo);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $geo);
        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_GET_INTERSECTING;
    }
}
