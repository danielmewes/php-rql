<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class WithFields extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes)
    {
        // The same comment as in pluck applies.
        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }
        $attributes = \r\nativeToDatum($attributes);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributes);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_WITH_FIELDS;
    }
}
