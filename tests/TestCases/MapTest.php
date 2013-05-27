<?php

class MapTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->map(function($hero) {
            return $hero('combatPower')->add($hero('compassionPower')->mul(2));
          }),
            array(7.0, 9.0, 5.0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->map(r\row('combatPower')->add(r\row('compassionPower')->mul(2))),
            array(7.0, 9.0, 5.0));
            
        $this->checkQueryResult(r\expr(array(r\db('Heroes')->table('marvel')->coerceTo('array'), r\db('Heroes')->table('marvel')->coerceTo('array')))->concatMap( function ($hero) { return $hero->pluck('superhero');} )->map(r\row('superhero')),
            array('Iron Man', 'Wolverine', 'Spiderman', 'Iron Man', 'Wolverine', 'Spiderman'));
        $this->checkQueryResult(r\expr(array(r\db('Heroes')->table('marvel')->coerceTo('array'), r\db('Heroes')->table('marvel')->coerceTo('array')))->concatMap(r\row()->pluck('superhero'))->map(r\row('superhero')),
            array('Iron Man', 'Wolverine', 'Spiderman', 'Iron Man', 'Wolverine', 'Spiderman'));
    }
}

?>
