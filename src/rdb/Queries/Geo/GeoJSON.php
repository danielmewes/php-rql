<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class GeoJSON extends ValuedQuery
{
    public function __construct($geojson)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($geojson));
    }

    protected function getTermType()
    {
        return Term_TermType::PB_GEOJSON;
    }
}
