<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class GetNearest extends ValuedQuery
{
    public function __construct(Table $table, $center, array $opts = [])
    {
        $center = $this->nativeToDatum($center);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $center);
        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_GET_NEAREST;
    }
}
