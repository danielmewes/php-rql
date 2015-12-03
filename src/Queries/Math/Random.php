<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Random extends ValuedQuery
{
    public function __construct($left = null, $right = null, $opts = null)
    {
        if (isset($left)) {
            $this->setPositionalArg(0, $this->nativeToDatum($left));
        }
        if (isset($right)) {
            $this->setPositionalArg(1, $this->nativeToDatum($right));
        }
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_RANDOM;
    }
}
