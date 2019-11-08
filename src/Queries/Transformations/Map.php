<?php

namespace r\Queries\Transformations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Map extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $mappingFunction)
    {
        $mappingFunction = $this->nativeToFunction($mappingFunction);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $mappingFunction);
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_MAP;
    }
}
