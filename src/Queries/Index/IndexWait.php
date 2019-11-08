<?php

namespace r\Queries\Index;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class IndexWait extends ValuedQuery
{
    public function __construct(Table $table, ...$indexNames)
    {
        $this->setPositionalArg(0, $table);
        $pos = 1;
        foreach ($indexNames as $v) {
            $this->setPositionalArg($pos++, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_INDEX_WAIT;
    }
}
