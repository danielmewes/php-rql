<?php

namespace r\Queries\Dates;

use r\ValuedQuery\ValuedQuery;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class During extends ValuedQuery
{
    public function __construct(ValuedQuery $time, $startTime, $endTime, $opts = null)
    {
        $startTime = \r\nativeToDatum($startTime);
        $endTime = \r\nativeToDatum($endTime);

        $this->setPositionalArg(0, $time);
        $this->setPositionalArg(1, $startTime);
        $this->setPositionalArg(2, $endTime);
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
        return Term_TermType::PB_DURING;
    }
}
