<?php

namespace r\ValuedQuery;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class Literal extends ValuedQuery
{
    public function __construct()
    {
        if (func_num_args() > 0) {
            $value = func_get_arg(0);
            if (!(is_object($value) && is_subclass_of($value, "\\r\\Query"))) {
                $value = \r\nativeToDatum($value);
            }
            $this->setPositionalArg(0, $value);
        }
    }

    protected function getTermType()
    {
        return Term_TermType::PB_LITERAL;
    }
}
