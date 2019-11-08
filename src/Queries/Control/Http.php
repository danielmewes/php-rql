<?php

namespace r\Queries\Control;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Http extends ValuedQuery
{
    public function __construct($url, array $opts = [])
    {
        $this->setPositionalArg(0, $this->nativeToDatum($url));
        foreach ($opts as $opt => $val) {
            $this->setOptionalArg($opt, $this->nativeToDatum($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_HTTP;
    }
}
