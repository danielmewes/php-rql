<?php

namespace r\Queries\Manipulation;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Pluck extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $attributes)
    {
        // It would appear that the new pattern-matching syntax in 1.7 would make this
        // a little cumbersome. The problem seems to be that we must distinguish
        // pattern such as array('a' => true) from a list of field names such as
        // array('a', 'b').
        // Luckily it turns out, that the new interface also supports passing in a plain
        // ArrayDatum, which will be interpreted correctly. So we can just always
        // interpret arrays as patterns.

        if (!is_array($attributes)) {
            $attributes = array($attributes);
        }
        $attributes = $this->nativeToDatum($attributes);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $attributes);
    }

    protected function getTermType()
    {
        return TermTermType::PB_PLUCK;
    }
}
