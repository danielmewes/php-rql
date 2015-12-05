<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Changes extends ValuedQuery
{
    public function __construct(ValuedQuery $src, $opts = null)
    {
        $this->setPositionalArg(0, $src);
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_CHANGES;
    }
}
