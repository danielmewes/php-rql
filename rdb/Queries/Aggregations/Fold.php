<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Fold extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $base, $fun, $opts = null)
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatum($base));
        $this->setPositionalArg(2, $this->nativeToFunction($fun));

        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatumOrFunction($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_FOLD;
    }
}
