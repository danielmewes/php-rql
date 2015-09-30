<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Distinct extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $opts = null)
    {
        $this->setPositionalArg(0, $sequence);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, \r\nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_DISTINCT;
    }
}
