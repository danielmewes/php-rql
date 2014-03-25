<?php

class AggregationsTest extends TestCase
{
    public function run()
    {    
        $this->checkQueryResult(r\expr(array(1, 2, 3, 4))->reduce(function($a, $b) { return $a->add($b); }),
            10.0);
            
        $this->checkQueryResult(r\expr(array(1, 2, 3, 4))->count(),
            4.0);
        $this->checkQueryResult(r\expr(array(1, 2, 3, 4))->count(2),
            1.0);
        $this->checkQueryResult(r\expr(array(1, 2, 3, 4))->count(r\row()->lt(3)),
            2.0);
            
        $this->checkQueryResult(r\expr(array(1, 2, 2, 4))->distinct(),
            array(1, 2, 4));
            
        $this->checkQueryResult(r\expr(array(1, 2, 2, 4))
                ->group(function ($r) { return $r; })
                ->map(function ($r) { return $r; })
                ->reduce(function($a, $b) { return $a->add($b); })
                ->ungroup(),
            array(array('reduction' => 1, 'group' => 1), array('reduction' => 4, 'group' => 2), array('reduction' => 4, 'group' => 4)));

         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 2), array('v' => 4)))->group('v')->count()->ungroup(),
            array(array('reduction' => 1, 'group' => 1), array('reduction' => 2, 'group' => 2), array('reduction' => 1, 'group' =>  4)));
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 2), array('v' => 4)))->group('v')->sum('v')->ungroup(),
            array(array('reduction' => 1, 'group' => 1), array('reduction' => 4, 'group' => 2), array('reduction' => 4, 'group' => 4)));
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 2), array('v' => 4)))->group('v')->avg('v')->ungroup(),
            array(array('reduction' => 1, 'group' => 1), array('reduction' => 2, 'group' => 2), array('reduction' => 4, 'group' => 4)));
         $this->checkQueryResult(r\expr(array(array('v' => 1, 'x' => 1), array('v' => 2, 'x' => 2), array('v' => 2, 'x' => 3), array('v' => 4, 'x' => 4)))->group(array('v', 'x'))->count()->ungroup(),
            array(array('reduction' => 1, 'group' => array('v' => 1, 'x' => 1)), array('reduction' => 1, 'group' => array('v' => 2, 'x' => 2)), array('reduction' => 1, 'group' => array('v' => 2, 'x' => 3)), array('reduction' => 1, 'group' => array('v' => 4, 'x' => 4))));

         $this->checkQueryResult(r\expr(array(1, 2, 3))->count(), 3.0);
         $this->checkQueryResult(r\expr(array(1, 2, 3))->sum(), 6.0);
         $this->checkQueryResult(r\expr(array(1, 2, 3))->avg(), 2.0);
         $this->checkQueryResult(r\expr(array(1, 2, 3))->max(), 3.0);
         $this->checkQueryResult(r\expr(array(1, 2, 3))->min(), 1.0);
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 3)))->sum('v'), 6.0);
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 3)))->avg('v'), 2.0);
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 3)))->max('v'), array('v' => 3.0));
         $this->checkQueryResult(r\expr(array(array('v' => 1), array('v' => 2), array('v' => 3)))->min('v'), array('v' => 1.0));

         $this->checkQueryResult(r\expr(array('a', 'b', 'c'))->contains('a'), true);
         $this->checkQueryResult(r\expr(array('a', 'b', 'c'))->contains('z'), false);
         $this->checkQueryResult(r\expr(array('a', 'b', 'c'))->contains(r\row()->eq('a')), true);
         $this->checkQueryResult(r\expr(array('a', 'b', 'c'))->contains(r\row()->eq('z')), false);
         $this->checkQueryResult(r\expr(array('a', 'b', 'c'))->contains(function ($x) {return $x->eq('a');}), true);
         $this->checkQueryResult(r\expr(array('a', 'b', 'c'))->contains(function ($x) {return $x->eq('z');}), false);
    }
}

?>
