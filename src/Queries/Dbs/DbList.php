<?php

namespace r\Queries\Dbs;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class DbList extends ValuedQuery
{
    protected function getTermType(): int
    {
        return TermTermType::PB_DB_LIST;
    }
}
