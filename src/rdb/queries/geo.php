<?php namespace r;

class GeoJSON extends ValuedQuery
{
    public function __construct($geojson) {
        $this->setPositionalArg(0, nativeToDatum($geojson));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GEOJSON;
    }
}

class ToGeoJSON extends ValuedQuery
{
    public function __construct($geometry) {
        $this->setPositionalArg(0, nativeToDatum($geometry));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_TOGEOJSON;
    }
}

class Point extends ValuedQuery
{
    public function __construct($lat, $lon) {
        $this->setPositionalArg(0, nativeToDatum($lat));
        $this->setPositionalArg(1, nativeToDatum($lon));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_POINT;
    }
}

class Line extends ValuedQuery
{
    public function __construct($points) {
        if (!is_array($points)) {
            throw new RqlDriverError("Points must be an array.");
        }
        $i = 0;
        foreach ($points as $point) {
            $this->setPositionalArg($i++, nativeToDatum($point));
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_LINE;
    }
}

class Polygon extends ValuedQuery
{
    public function __construct($points) {
        if (!is_array($points)) {
            throw new RqlDriverError("Points must be an array.");
        }
        $i = 0;
        foreach ($points as $point) {
            $this->setPositionalArg($i++, nativeToDatum($point));
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_POLYGON;
    }
}

class Intersects extends ValuedQuery
{
    public function __construct(ValuedQuery $reference, $geometry) {
        $this->setPositionalArg(0, $reference);
        $this->setPositionalArg(1, nativeToDatum($geometry));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INTERSECTS;
    }
}

?>
