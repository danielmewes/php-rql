<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class MapMultiple extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $moreSequences, $mappingFunction)
    {
        if (!is_array($moreSequences)) {
            $moreSequences = array($moreSequences);
        }
        $mappingFunction = $this->nativeToFunction($mappingFunction);

        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($moreSequences as $seq) {
            $this->setPositionalArg($i++, $seq);
        }
        $this->setPositionalArg($i, $mappingFunction);
    }

    protected function getTermType()
    {
        return TermTermType::PB_MAP;
    }
}
