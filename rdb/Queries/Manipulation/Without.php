<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Without extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes)
    {
        // See comment above about pluck. The same applies here.
        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }
        $attributes = $this->nativeToDatum($attributes);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributes);
    }

    protected function getTermType()
    {
        return TermTermType::PB_WITHOUT;
    }
}
