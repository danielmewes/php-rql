<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class ConcatMap extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $mappingFunction)
    {
        $mappingFunction = $this->nativeToFunction($mappingFunction);

        $this->setPositionalArg(0, $sequence);
        $this->setPositionalArg(1, $mappingFunction);
    }

    protected function getTermType()
    {
        return TermTermType::PB_CONCAT_MAP;
    }
}
