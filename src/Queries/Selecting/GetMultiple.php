<?php

namespace r\Queries\Selecting;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class GetMultiple extends ValuedQuery
{
    public function __construct(Table $table, array $keys, array $opts = [])
    {
        $this->setPositionalArg(0, $table);

        $i = 1;
        foreach ($keys as $key) {
            $this->setPositionalArg($i++, $this->nativeToDatum($key));
        }

        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_GET_ALL;
    }
}
