<?php

class AggregationsTest extends TestCase
{
    public function run()
    {    
        $this->checkQueryResult(r\expr(array(1, 2, 3, 4))->reduce(function($a, $b) { return $a->add($b); }),
            10.0);
        $this->checkQueryResult(r\expr(array(1, 2, 3, 4))->reduce(function($a, $b) { return $a->add($b); }, 5),
            15.0);
            
        $this->checkQueryResult(r\expr(array(1, 2, 3, 4))->count(),
            4.0);
            
        $this->checkQueryResult(r\expr(array(1, 2, 2, 4))->distinct(),
            array(1, 2, 4));
            
        $this->checkQueryResult(r\expr(array(1, 2, 2, 4))->groupedMapReduce(
                function ($r) { return $r; },
                function ($r) { return $r; },
                function($a, $b) { return $a->add($b); }
            ),
            array(array('reduction' => 1, 'group' => 1), array('reduction' => 4, 'group' => 2), array('reduction' => 4, 'group' => 4)));
            
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 2), array('v' => 4)))->groupBy('v', r\count()),
            array(array('reduction' => 1, 'group' => array(1)), array('reduction' => 2, 'group' => array(2)), array('reduction' => 1, 'group' => array(4))));
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 2), array('v' => 4)))->groupBy('v', r\sum('v')),
            array(array('reduction' => 1, 'group' => array(1)), array('reduction' => 4, 'group' => array(2)), array('reduction' => 4, 'group' => array(4))));
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 2), array('v' => 4)))->groupBy('v', r\avg('v')),
            array(array('reduction' => 1, 'group' => array(1)), array('reduction' => 2, 'group' => array(2)), array('reduction' => 4, 'group' => array(4))));
         $this->checkQueryResult(r\expr(array(array('v' => 1, 'x' => 1), array('v' => 2, 'x' => 2), array('v' => 2, 'x' => 3), array('v' => 4, 'x' => 4)))->groupBy(array('v', 'x'), r\count()),
            array(array('reduction' => 1, 'group' => array(1, 1)), array('reduction' => 1, 'group' => array(2, 2)), array('reduction' => 1, 'group' => array(2, 3)), array('reduction' => 1, 'group' => array(4, 4))));
    }
}

?>
