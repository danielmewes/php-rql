<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\ProtocolBuffer\TermTermType;

class GeoJSON extends ValuedQuery
{
    public function __construct($geojson)
    {
        $this->setPositionalArg(0, $this->nativeToDatum($geojson));
    }

    protected function getTermType()
    {
        return TermTermType::PB_GEOJSON;
    }
}
