<?php

namespace r\Queries\Aggregations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class Group extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $fieldOrFunction, $groupOn = array())
    {
        array_unshift($groupOn, $fieldOrFunction);
        
        if (isset($groupOn['index'])) {
            $this->setOptionalArg('index', $this->nativeToDatum($groupOn['index']));
            unset($groupOn['index']);
        }
        
          if (isset($groupOn['multi'])) {
            $this->setOptionalArg('multi', $this->nativeToDatum($groupOn['multi']));
            unset($groupOn['multi']);
        }

        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($groupOn as $g) {
            $this->setPositionalArg($i++, $this->nativeToDatumOrFunction($g));
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_GROUP;
    }
}
