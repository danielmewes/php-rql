<?php

class UpsertTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->insert(array( 'superhero' => 'Iron Man', 'superpower' => 'Arc Reactor', 'combatPower' => 2.0, 'compassionPower' => 1.5), true), array('unchanged' => 1, 'skipped' => 0, 'replaced' => 0, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->insert(array( 'superhero' => 'Iron Man', 'superpower' => 'Suit' ), true), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 1, 'inserted' => 0, 'errors' => 0, 'deleted' => 0));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->insert(array( 'superhero' => 'Pepper', 'superpower' => 'Stark Industries' ), true), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 0, 'inserted' => 1, 'errors' => 0, 'deleted' => 0));
        
        $this->datasets['Heroes']->reset();
    }
}

?>
