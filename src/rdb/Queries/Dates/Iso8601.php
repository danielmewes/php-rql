<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class Iso8601 extends ValuedQuery
{
    public function __construct($iso8601Date, $opts = null)
    {
        $iso8601Date = \r\nativeToDatum($iso8601Date);

        $this->setPositionalArg(0, $iso8601Date);
        if (isset($opts)) {
            if (!is_array($opts)) {
                throw new RqlDriverError("opts argument must be an array");
            }
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, \r\nativeToDatum($v));
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_ISO8601;
    }
}
