<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Map extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $mappingFunction)
    {
        $mappingFunction = \r\nativeToFunction($mappingFunction);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $mappingFunction);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_MAP;
    }
}
