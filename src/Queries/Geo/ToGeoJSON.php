<?php

namespace r\Queries\Geo;

use r\ProtocolBuffer\TermTermType;
use r\ValuedQuery\ValuedQuery;

class ToGeoJSON extends ValuedQuery
{
    public function __construct($geometry)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($geometry));
    }

    protected function getTermType(): int
    {
        return TermTermType::PB_TO_GEOJSON;
    }
}
