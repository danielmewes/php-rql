<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Changes extends ValuedQuery
{
    public function __construct(ValuedQuery $src, array $opts = [])
    {
        $this->setPositionalArg(0, $src);
        foreach ($opts as $opt => $val) {
            $this->setOptionalArg($opt, $this->nativeToDatum($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_CHANGES;
    }
}
