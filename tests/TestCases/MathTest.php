<?php

class MathTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\expr('a')->match('b'),
            null);
        $this->checkQueryResult(r\expr('b')->match('b'),
            array('str' => 'b', 'start' => 0, 'groups' => array(), 'end' => 1));
        $this->checkQueryResult(r\expr('id:0,name:mlucy,foo:bar')->match('name:(\w+)'),
            array('str' => 'name:mlucy', 'start' => 5, 'groups' => array(array('str' => 'mlucy', 'start' => 10, 'end' => 15)), 'end' => 15));

        $this->checkQueryResult(r\expr('aA')->upcase(),
            "AA");
        $this->checkQueryResult(r\expr('aA')->downcase(),
            "aa");

        $this->checkQueryResult(r\expr('foo bar bax')->split(),
            array('foo', 'bar', 'bax'));
        $this->checkQueryResult(r\expr('foo,bar,bax')->split(","),
            array('foo', 'bar', 'bax'));
        $this->checkQueryResult(r\expr('foo')->split(""),
            array('f', 'o', 'o'));
        $this->checkQueryResult(r\expr('foo bar bax')->split(null, 1),
            array('foo', 'bar bax'));

        $this->checkQueryResult(r\expr('a')->add('b'),
            'ab');
        $this->checkQueryResult(r\expr(1)->add(2),
            3.0);
        $this->checkQueryResult(r\expr(1)->sub(2),
            -1.0);
        $this->checkQueryResult(r\expr(1)->mul(2),
            2.0);
        $this->checkQueryResult(r\expr(1)->div(2),
            0.5);
        $this->checkQueryResult(r\expr(1)->mod(2),
            1.0);

        $this->checkQueryResult(r\expr(true)->rAnd(true),
            true);
        $this->checkQueryResult(r\expr(true)->rAnd(false),
            false);
        $this->checkQueryResult(r\expr(false)->rAnd(true),
            false);
        $this->checkQueryResult(r\expr(false)->rAnd(false),
            false);

        $this->checkQueryResult(r\expr(true)->rOr(true),
            true);
        $this->checkQueryResult(r\expr(true)->rOr(false),
            true);
        $this->checkQueryResult(r\expr(false)->rOr(true),
            true);
        $this->checkQueryResult(r\expr(false)->rOr(false),
            false);

        $this->checkQueryResult(r\expr(1.0)->eq(1.0),
            true);
        $this->checkQueryResult(r\expr(1.0)->eq(-1.0),
            false);

        $this->checkQueryResult(r\expr(1.0)->ne(1.0),
            false);
        $this->checkQueryResult(r\expr(1.0)->ne(-1.0),
            true);

        $this->checkQueryResult(r\expr(1.0)->gt(1.0),
            false);
        $this->checkQueryResult(r\expr(1.0)->gt(-1.0),
            true);

        $this->checkQueryResult(r\expr(1.0)->ge(1.0),
            true);
        $this->checkQueryResult(r\expr(1.0)->ge(-1.0),
            true);

        $this->checkQueryResult(r\expr(1.0)->lt(1.0),
            false);
        $this->checkQueryResult(r\expr(1.0)->lt(-1.0),
            false);

        $this->checkQueryResult(r\expr(1.0)->le(1.0),
            true);
        $this->checkQueryResult(r\expr(1.0)->le(-1.0),
            false);

        $this->checkQueryResult(r\expr(true)->not(),
            false);
        $this->checkQueryResult(r\expr(false)->not(),
            true);

        $this->checkQueryResult(r\add(r\expr(3), r\expr(2)),
            5.0);
        $this->checkQueryResult(r\sub(r\expr(3), r\expr(2)),
            1.0);
        $this->checkQueryResult(r\mul(r\expr(3), r\expr(2)),
            6.0);
        $this->checkQueryResult(r\div(r\expr(7), r\expr(2)),
            3.5);
        $this->checkQueryResult(r\mod(r\expr(7), r\expr(2)),
            1.0);

        $this->checkQueryResult(r\rAnd(r\expr(true), r\expr(true)),
            true);
        $this->checkQueryResult(r\rAnd(r\expr(true), r\expr(false)),
            false);
        $this->checkQueryResult(r\rAnd(r\expr(false), r\expr(true)),
            false);
        $this->checkQueryResult(r\rAnd(r\expr(false), r\expr(false)),
            false);
        $this->checkQueryResult(r\rOr(r\expr(true), r\expr(true)),
            true);
        $this->checkQueryResult(r\rOr(r\expr(true), r\expr(false)),
            true);
        $this->checkQueryResult(r\rOr(r\expr(false), r\expr(true)),
            true);
        $this->checkQueryResult(r\rOr(r\expr(false), r\expr(false)),
            false);

        $this->checkQueryResult(r\eq(r\expr(5), r\expr(6)),
            false);
        $this->checkQueryResult(r\eq(r\expr(6), r\expr(6)),
            true);
        $this->checkQueryResult(r\ne(r\expr(5), r\expr(6)),
            true);
        $this->checkQueryResult(r\ne(r\expr(6), r\expr(6)),
            false);
        $this->checkQueryResult(r\gt(r\expr(5), r\expr(6)),
            false);
        $this->checkQueryResult(r\gt(r\expr(6), r\expr(6)),
            false);
        $this->checkQueryResult(r\gt(r\expr(6), r\expr(5)),
            true);
        $this->checkQueryResult(r\ge(r\expr(5), r\expr(6)),
            false);
        $this->checkQueryResult(r\ge(r\expr(6), r\expr(6)),
            true);
        $this->checkQueryResult(r\ge(r\expr(6), r\expr(5)),
            true);
        $this->checkQueryResult(r\lt(r\expr(5), r\expr(6)),
            true);
        $this->checkQueryResult(r\lt(r\expr(6), r\expr(6)),
            false);
        $this->checkQueryResult(r\lt(r\expr(6), r\expr(5)),
            false);
        $this->checkQueryResult(r\le(r\expr(5), r\expr(6)),
            true);
        $this->checkQueryResult(r\le(r\expr(6), r\expr(6)),
            true);
        $this->checkQueryResult(r\le(r\expr(6), r\expr(5)),
            false);

        $this->checkQueryResult(r\not(r\expr(true)),
            false);
        $this->checkQueryResult(r\not(r\expr(false)),
            true);
    }
}

?>
