<?php

namespace r\Queries\Geo;

use r\ValuedQuery\ValuedQuery;
use r\pb\Term_TermType;

class ToGeoJSON extends ValuedQuery
{
    public function __construct($geometry)
    {
        $this->setPositionalArg(0, \r\nativeToDatum($geometry));
    }

    protected function getTermType()
    {
        return Term_TermType::PB_TO_GEOJSON;
    }
}
