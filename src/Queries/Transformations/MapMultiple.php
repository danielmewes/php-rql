<?php

namespace r\Queries\Transformations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class MapMultiple extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $moreSequences, $mappingFunction)
    {
        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ((array) $moreSequences as $seq) {
            $this->setPositionalArg($i++, $seq);
        }
        $this->setPositionalArg($i, $this->nativeToFunction($mappingFunction));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_MAP;
    }
}
