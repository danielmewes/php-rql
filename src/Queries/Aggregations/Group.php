<?php

namespace r\Queries\Aggregations;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class Group extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $groupOn)
    {
        $groupOn = (array) $groupOn;

        if (isset($groupOn['index'])) {
            $this->setOptionalArg('index', $this->nativeToDatum($groupOn['index']));
            unset($groupOn['index']);
        }

        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($groupOn as $g) {
            $this->setPositionalArg($i++, $this->nativeToDatumOrFunction($g));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_GROUP;
    }
}
