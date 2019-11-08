<?php

namespace r\Queries\Transformations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class OrderBy extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, ...$keys)
    {
        $this->setPositionalArg(0, $sequence);
        $i = 1;

        foreach ($keys as $val) {
            if (is_array($val) && isset($val['index'])) {
                $this->setOptionalArg('index', $this->nativeToDatum($val['index']));
                continue;
            }

            $this->setPositionalArg($i++, $this->nativeToDatumOrFunction($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_ORDER_BY;
    }
}
