<?php

namespace r\Queries\Aggregations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Fold extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $base, $fun, array $opts = [])
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatum($base));
        $this->setPositionalArg(2, $this->nativeToFunction($fun));

        foreach ($opts as $opt => $val) {
            $this->setOptionalArg($opt, $this->nativeToDatumOrFunction($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_FOLD;
    }
}
