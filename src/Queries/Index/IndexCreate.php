<?php

namespace r\Queries\Index;

use r\ProtocolBuffer\TermTermType;
use r\Queries\Tables\Table;
use r\ValuedQuery\ValuedQuery;

class IndexCreate extends ValuedQuery
{
    public function __construct(Table $table, $indexName, $keyFunction = null, array $options = [])
    {
        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $this->nativeToDatum($indexName));

        if (isset($keyFunction)) {
            $this->setPositionalArg(2, $this->nativeToFunction($keyFunction));
        }

        foreach ($options as $key => $val) {
            $this->setOptionalArg($key, $this->nativeToDatum($val));
        }
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_INDEX_CREATE;
    }
}
