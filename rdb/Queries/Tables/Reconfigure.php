<?php

namespace r\Queries\Tables;

use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Reconfigure extends ValuedQuery
{
    public function __construct(Query $tables, $opts = null)
    {
        $this->setPositionalArg(0, $tables);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_RECONFIGURE;
    }
}
