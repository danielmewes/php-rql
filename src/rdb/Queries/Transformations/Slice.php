<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\Datum\NumberDatum;
use r\Datum\StringDatum;
use r\Exceptions\RqlDriverError;
use r\pb\Term_TermType;

class Slice extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $startIndex, $endIndex = null, $opts = null)
    {
        $startIndex = \r\nativeToDatum($startIndex);
        if (isset($endIndex)) {
            $endIndex = \r\nativeToDatum($endIndex);
        }

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $startIndex);
        if (isset($endIndex)) {
            $this->setPositionalArg(2, $endIndex);
        } else {
            $this->setPositionalArg(2, new NumberDatum(-1));
            $this->setOptionalArg('right_bound', new StringDatum('closed'));
        }
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
        return Term_TermType::PB_SLICE;
    }
}
