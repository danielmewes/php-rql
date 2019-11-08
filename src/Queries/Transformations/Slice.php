<?php

namespace r\Queries\Transformations;

use r\Datum\NumberDatum;
use r\Datum\StringDatum;
use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Slice extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $startIndex, $endIndex = null, array $opts = [])
    {
        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $this->nativeToDatum($startIndex));
        if (isset($endIndex)) {
            $this->setPositionalArg(2, $this->nativeToDatum($endIndex));
        } else {
            $this->setPositionalArg(2, new NumberDatum(-1));
            $this->setOptionalArg('right_bound', new StringDatum('closed'));
        }
        foreach ($opts as $k => $v) {
            $this->setOptionalArg($k, $this->nativeToDatum($v));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_SLICE;
    }
}
