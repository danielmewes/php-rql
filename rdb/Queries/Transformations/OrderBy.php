<?php

namespace r\Queries\Transformations;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class OrderBy extends ValuedQuery
{
    public function __construct(ValuedQuery $sequence, $keys)
    {
        if (!is_array($keys)) {
            $keys = array($keys);
        }
        // Check keys and convert strings
        if (isset($keys['index'])) {
            $this->setOptionalArg('index', $this->nativeToDatum($keys['index']));
            unset($keys['index']);
        }
        foreach ($keys as &$val) {
            if (!(is_object($val) && is_subclass_of($val, "\\r\\Ordering"))) {
                $val = $this->nativeToDatumOrFunction($val);
            }
            unset($val);
        }

        $this->setPositionalArg(0, $sequence);
        $i = 1;
        foreach ($keys as $key) {
            $this->setPositionalArg($i++, $key);
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_ORDER_BY;
    }
}
