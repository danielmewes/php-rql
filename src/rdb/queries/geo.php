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

class Circle extends ValuedQuery
{
    public function __construct($center, $radius, $opts) {
        $this->setPositionalArg(0, nativeToDatum($center));
        $this->setPositionalArg(1, nativeToDatum($radius));
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_CIRCLE;
    }
}

class Intersects extends ValuedQuery
{
    public function __construct($g1, $g2) {
        $this->setPositionalArg(0, nativeToDatum($g1));
        $this->setPositionalArg(1, nativeToDatum($g2));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INTERSECTS;
    }
}

class Includes extends ValuedQuery
{
    public function __construct($g1, $g2) {
        $this->setPositionalArg(0, nativeToDatum($g1));
        $this->setPositionalArg(1, nativeToDatum($g2));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_INCLUDES;
    }
}

class Distance extends ValuedQuery
{
    public function __construct($g1, $g2, $opts = null) {
        $this->setPositionalArg(0, nativeToDatum($g1));
        $this->setPositionalArg(1, nativeToDatum($g2));
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_DISTANCE;
    }
}

class GetIntersecting extends ValuedQuery
{
    public function __construct(Table $table, $geo, $opts = null) {
        $geo = nativeToDatum($geo);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $geo);
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GET_INTERSECTING;
    }
}

class GetNearest extends ValuedQuery
{
    public function __construct(Table $table, $center, $opts = null) {
        $center = nativeToDatum($center);

        $this->setPositionalArg(0, $table);
        $this->setPositionalArg(1, $center);
        if (isset($opts)) {
            if (!is_array($opts)) throw new RqlDriverError("opts argument must be an array");
            foreach ($opts as $k => $v) {
                $this->setOptionalArg($k, nativeToDatum($v));
            }
        }
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_GET_NEAREST;
    }
}

class Fill extends ValuedQuery
{
    public function __construct($g1) {
        $this->setPositionalArg(0, nativeToDatum($g1));
    }
    
    protected function getTermType() {
        return pb\Term_TermType::PB_FILL;
    }
}

?>
