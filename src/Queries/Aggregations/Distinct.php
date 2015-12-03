<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Distinct extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $opts = null)
    {
        $this->setPositionalArg(0, $sequence);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_DISTINCT;
    }
}
