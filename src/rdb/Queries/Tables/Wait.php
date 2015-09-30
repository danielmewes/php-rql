<?php

namespace r\Queries\Tables;

use r\Query;
use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Wait extends ValuedQuery
{
    public function __construct(Query $tables, $opts = null)
    {
        $this->setPositionalArg(0, $tables);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, \r\nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_WAIT;
    }
}
