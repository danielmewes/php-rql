<?php

class ControlTest extends TestCase
{
    public function run()
    {   
        $this->requireDataset('Control');
        $this->requireDataset('Heroes');
    
        $this->checkQueryResult(r\rDo(array(1, 2, 3), function($x, $y, $z) { return $x->mul($y->add($z));}),
            5.0);
            
        $this->checkQueryResult(r\branch(r\expr(true), r\expr('true'), r\expr('false')),
            'true');
        $this->checkQueryResult(r\branch(r\expr(false), r\expr('true'), r\expr('false')),
            'false');
            
        $this->checkQueryResult(r\expr(array(1, 2, 3))->rForeach(function($x) { return r\db('Control')->table('t1')->insert(array('x' => $x));})->attr('inserted'),
            3.0);
        $this->checkQueryResult(r\db('Control')->table('t1')->map(r\row('x')),
            array(1, 2, 3));
            
        $errorCaught = false;
        try {
            r\error('ERRRRRR')->run($this->conn);
        } catch (r\RqlUserError $e) {
            $errorCaught = true;
        }
        if (!$errorCaught) echo "r\error() did not throw an error.\n";
        
        // Js is also tested separately in JsTest
        $this->checkQueryResult(r\js("'str1' + 'str2'"), 'str1str2');
        
        $this->checkQueryResult(r\expr('5.0')->coerceTo('number'), 5.0);
        $this->checkQueryResult(r\expr(5.0)->coerceTo('string'), '5');
        
        $this->checkQueryResult(r\expr(5.0)->typeOf(), 'NUMBER');
        $this->checkQueryResult(r\expr('foo')->typeOf(), 'STRING');
        $this->checkQueryResult(r\expr(null)->typeOf(), 'NULL');
        $this->checkQueryResult(r\expr(array(1, 2, 3))->typeOf(), 'ARRAY');
        $this->checkQueryResult(r\expr(array('x' => 1))->typeOf(), 'OBJECT');
        
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->info(),
            array('type' => "TABLE", 'primary_key' => 'superhero', 'name' => 'marvel', 'indexes' => array(), 'db' => array('type' => 'DB', 'name' => 'Heroes') ));
            
        $this->checkQueryResult(r\expr(array('a' => 4))->attr('a')->rDefault(5), 4.0);
        $this->checkQueryResult(r\expr(array('a' => 4))->attr('b')->rDefault(5), 5.0);
        $this->checkQueryResult(r\expr(array('a' => 4))->attr('b')->rDefault(function ($e) { return r\expr(5); } ), 5.0);
        
        $this->datasets['Control']->reset();
    }
}

?>
