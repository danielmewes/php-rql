<?php

namespace r\Queries\Control;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Http extends ValuedQuery
{
    public function __construct($url, $opts = null)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($url));
        if (isset($opts)) {
            foreach ($opts as $opt => $val) {
                $this->setOptionalArg($opt, \r\nativeToDatum($val));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_HTTP;
    }
}
