<?php

namespace r\Queries\Aggregations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Min extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributeOrOpts = null)
    {
        $this->setPositionalArg(0, $sequence);
        if (isset($attributeOrOpts)) {
            if (is_array($attributeOrOpts)) {
                foreach ($attributeOrOpts as $opt => $val) {
                    $this->setOptionalArg($opt, $this->nativeToDatum($val));
                }
            } else {
                $attribute = $this->nativeToDatumOrFunction($attributeOrOpts);
                $this->setPositionalArg(1, $attribute);
            }
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_MIN;
    }
}
