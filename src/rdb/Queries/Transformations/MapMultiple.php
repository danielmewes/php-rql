<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class MapMultiple extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $moreSequences, $mappingFunction)
    {
        if (!is_array($moreSequences)) {
            $moreSequences = array($moreSequences);
        }
        $mappingFunction = \r\nativeToFunction($mappingFunction);

        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($moreSequences as $seq) {
            $this->setPositionalArg($i++, $seq);
        }
        $this->setPositionalArg($i, $mappingFunction);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_MAP;
    }
}
