<?php

namespace r\ValuedQuery;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class ImplicitVar extends ValuedQuery
{
    protected function getTermType()
    {
        return TermTermType::PB_IMPLICIT_VAR;
    }
    public function hasUnwrappedImplicitVar()
    {
        // A function wraps implicit variables
        return true;
    }
}
