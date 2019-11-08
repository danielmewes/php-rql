<?php

namespace r\ValuedQuery;

use r\ProtocolBuffer\TermTermType;

class ImplicitVar extends ValuedQuery
{
    protected function getTermType(): int
    {
        return TermTermType::PB_IMPLICIT_VAR;
    }

    public function hasUnwrappedImplicitVar(): bool
    {
        // A function wraps implicit variables
        return true;
    }
}
