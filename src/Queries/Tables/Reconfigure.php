<?php

namespace r\Queries\Tables;

use r\ProtocolBuffer\TermTermType;
use r\Query;
use r\ValuedQuery\ValuedQuery;

class Reconfigure extends ValuedQuery
{
    public function __construct(Query $tables, array $opts = [])
    {
        $this->setPositionalArg(0, $tables);
        foreach ($opts as $opt => $val) {
            $this->setOptionalArg($opt, $this->nativeToDatum($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_RECONFIGURE;
    }
}
