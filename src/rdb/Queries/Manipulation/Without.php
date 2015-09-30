<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Without extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes)
    {
        // See comment above about pluck. The same applies here.
        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }
        $attributes = \r\nativeToDatum($attributes);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributes);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_WITHOUT;
    }
}
