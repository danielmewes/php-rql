<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Min extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributeOrOpts = null)
    {
        $this->setPositionalArg(0, $sequence);
        if (isset($attributeOrOpts)) {
            if (is_array($attributeOrOpts)) {
                foreach ($attributeOrOpts as $opt => $val) {
                    $this->setOptionalArg($opt, \r\nativeToDatum($val));
                }
            } else {
                $attribute = \r\nativeToDatumOrFunction($attributeOrOpts);
                $this->setPositionalArg(1, $attribute);
            }
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_MIN;
    }
}
