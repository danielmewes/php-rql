<?php

namespace r\Queries\Math;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Random extends ValuedQuery
{
    public function __construct($left = null, $right = null, $opts = null)
    {
        if (isset($left)) {
            $this->setPositionalArg(0, \r\nativeToDatum($left));
        }
        if (isset($right)) {
            $this->setPositionalArg(1, \r\nativeToDatum($right));
        }
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, \r\nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_RANDOM;
    }
}
