<?php

namespace r\Ordering;

use r\Ordering\Ordering;
use r\pb\Term_TermType;

class Asc extends Ordering
{
    public function __construct($attribute)
    {
        $attribute = \r\nativeToDatumOrFunction($attribute);
        $this->setPositionalArg(0, $attribute);
    }

    protected function getTermType()
    {
        return Term_TermType::PB_ASC;
    }
}
