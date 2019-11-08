<?php

namespace r\Queries\Dates;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Iso8601 extends ValuedQuery
{
    public function __construct($iso8601Date, array $opts = [])
    {
        $iso8601Date = $this->nativeToDatum($iso8601Date);

        $this->setPositionalArg(0, $iso8601Date);

        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_ISO8601;
    }
}
