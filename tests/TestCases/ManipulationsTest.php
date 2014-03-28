<?php

class ManipulationsTest extends TestCase
{
    public function run()
    {    
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->filter(r\row()->attr('y')->eq(2))->pluck('x'),
            array(array('x' => 1)));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->filter(r\row('y')->eq(2))->pluck('x'),
            array(array('x' => 1)));
        
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->pluck('x'),
            array(array('x' => 1)));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->pluck(array('x', 'y')),
            array(array('x' => 1, 'y' => 2)));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))->pluck(array('x' => true)),
            array(array('x' => 1)));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))->pluck(array('y' => array('a', 'b'))),
            array(array('y' => array('a' => 2.1, 'b' => 2.2))));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))->pluck(array('y' => array('b' => true))),
            array(array('y' => array('b' => 2.2))));
            
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->without('x'),
            array(array('y' => 2)));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => 2)))->without(array('x', 'y')),
            array(array()));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))->without(array('x' => true)),
            array(array('y' => array('a' => 2.1, 'b' => 2.2))));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))->without(array('y' => array('a', 'b'))),
            array(array('x' => 1, 'y' => array())));
        $this->checkQueryResult(r\expr(array(array('x' => 1, 'y' => array('a' => 2.1, 'b' => 2.2))))->without(array('y' => array('b' => true))),
            array(array('x' => 1, 'y' => array('a' => 2.1))));
        
        $this->checkQueryResult(r\expr(array('x' => 1))->merge(array('y' => 2)),
            array('x' => 1, 'y' => 2));
        $this->checkQueryResult(r\expr(array('x' => 1))->merge(r\expr(array('y' => 2))),
            array('x' => 1, 'y' => 2));
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => array('a' => 1, 'b' => 2)))->merge(array('y' => array('c' => 3))),
            array('x' => 1, 'y' => array('a' => 1, 'b' => 2, 'c' => 3)));
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => array('a' => 1, 'b' => 2)))->merge(array('y' => r\literal(array('c' => 3)))),
            array('x' => 1, 'y' => array('c' => 3)));
        $this->checkQueryResult(r\expr(array(array('a' => 1), array('a' => 2)))->merge(function($doc) { return array('b' => $doc('a')->add(1)); }),
            array(array('a' => 1, 'b' => 2), array('a' => 2, 'b' => 3)));
            
        $this->checkQueryResult(r\expr(array(1, 2, 3))->append(4),
            array(1, 2, 3, 4));
        $this->checkQueryResult(r\expr(array(1, 2, 3))->append(r\expr(4)),
            array(1, 2, 3, 4));
        
        $this->checkQueryResult(r\expr(array(1, 2, 3))->prepend(4),
            array(4, 1, 2, 3));
        $this->checkQueryResult(r\expr(array(1, 2, 3))->prepend(r\expr(4)),
            array(4, 1, 2, 3));
            
        $this->checkQueryResult(r\expr(array(1, 2, 3))->difference(array(1, 2)),
            array(3));
        $this->checkQueryResult(r\expr(array(1, 2, 3))->difference(r\expr(array(1, 2))),
            array(3));
            
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => 2))->hasFields('x'),
            true);
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => 2))->hasFields('foo'),
            false);
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => 2))->hasFields(array('x', 'y')),
            true);
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => 2))->hasFields(array('x', 'foo')),
            false);
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => 2))->hasFields(array('x' => true)),
            true);
        $this->checkQueryResult(r\expr(array('x' => 1, 'y' => 2))->hasFields(array('foo' => true)),
            false);
            
        $this->checkQueryResult(r\expr(array(1, 2, 3))->setInsert(4),
            array(1, 2, 3, 4));
        $this->checkQueryResult(r\expr(array(1, 2, 3))->setInsert(1),
            array(1, 2, 3));
            
        $this->checkQueryResult(r\expr(array(1, 2, 3))->setUnion(array(1, 4)),
            array(1, 2, 3, 4));
        
        $this->checkQueryResult(r\expr(array(1, 2, 3))->setIntersection(array(1, 4)),
            array(1));
            
        $this->checkQueryResult(r\expr(array(1, 2, 3))->setDifference(array(1, 4)),
            array(2, 3));
            
        $this->checkQueryResult(r\expr(array('a' => 1, 'b' => 2, 'c' => 3))->keys(),
            array('a', 'b', 'c'));
            
        $this->checkQueryResult(r\expr(array("Iron Man", "Spider-Man"))->insertAt(1, "Hulk"),
            array("Iron Man", "Hulk", "Spider-Man"));
        
        $this->checkQueryResult(r\expr(array("Iron Man", "Spider-Man"))->spliceAt(1, array("Hulk", "Thor")),
            array("Iron Man", "Hulk", "Thor",  "Spider-Man"));
        
        $this->checkQueryResult(r\expr(array("Iron Man", "Hulk", "Spider-Man"))->deleteAt(1),
            array("Iron Man", "Spider-Man"));
        $this->checkQueryResult(r\expr(array("Iron Man", "Hulk", "Thor", "Spider-Man"))->deleteAt(1,2),
            array("Iron Man", "Thor", "Spider-Man"));
        // TODO: This is disabled due to a potential bug in the server as of RethinkDB 1.9.0: https://github.com/rethinkdb/rethinkdb/issues/1456
        /*$this->checkQueryResult(r\expr(array("Iron Man", "Hulk", "Thor", "Spider-Man"))->deleteAt(1,2, array('right_bound' => 'closed')),
            array("Iron Man", "Spider-Man"));*/
            
        $this->checkQueryResult(r\expr(array("Iron Man", "Bruce", "Spider-Man"))->changeAt(1, "Hulk"),
            array("Iron Man", "Hulk", "Spider-Man"));
    }
}

?>
