<?php

class Geo extends Dataset
{
    protected function create()
    {
        r\dbCreate('Geo')->run($this->conn);
        r\db('Geo')->tableCreate('geo')->run($this->conn);
        
        $geoTable = r\db('Geo')->table('geo');
        
        $geoTable->insert(array('geo' => r\point(1.0, 1.0)))->run($this->conn);
        $geoTable->insert(array('geo' => r\point(1.0, 0.0)))->run($this->conn);
        
        $geoTable->indexCreateGeo('geo')->run($this->conn);
        $geoTable->indexCreateMultiGeo('mgeo', function($x) {return r\expr(array($x('geo')));})->run($this->conn);
        $geoTable->indexWait('geo')->run($this->conn);
        $geoTable->indexWait('mgeo')->run($this->conn);
    }    
    
    protected function delete()
    {
        r\dbDrop('Geo')->run($this->conn);
    }
}

?>
