<?php

namespace r\Queries\Index;

use r\ValuedQuery\ValuedQuery;
use r\Queries\Tables\Table;
use r\ProtocolBuffer\TermTermType;

class IndexWait extends ValuedQuery
{
    public function __construct(Table $table, $indexNames = null)
    {
        if (isset($indexNames) && !is_array($indexNames)) {
            $indexNames = array($indexNames);
        }

        $this->setPositionalArg(0, $table);
        if (isset($indexNames)) {
            $pos = 1;
            foreach ($indexNames as $v) {
                $this->setPositionalArg($pos++, $this->nativeToDatum($v));
            }
        }
    }

    protected function getTermType()
    {
        return TermTermType::PB_INDEX_WAIT;
    }
}
