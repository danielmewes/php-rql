<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Http extends ValuedQuery
{
    public function __construct($url, $opts = null)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($url));
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, $this->nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_HTTP;
    }
}
