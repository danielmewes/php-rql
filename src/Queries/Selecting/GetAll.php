<?php

namespace r\Queries\Selecting;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class GetAll extends ValuedQuery
{
    public function __construct(Table $table, $key, array $opts = [])
    {
        $key = $this->nativeToDatum($key);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $key);
        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_GET_ALL;
    }
}
