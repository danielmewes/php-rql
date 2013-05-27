<?php

class ValueTest extends TestCase
{
    public function run()
    {
        $this->checkQueryResult(r\expr(null), null);
        $this->checkQueryResult(r\expr(true), true);
        $this->checkQueryResult(r\expr(false), false);
        $this->checkQueryResult(r\expr(0.5), 0.5);
        $this->checkQueryResult(r\expr(0), 0.0);
        $this->checkQueryResult(r\expr(-1), -1.0);
        $this->checkQueryResult(r\expr(1), 1.0);
        $this->checkQueryResult(r\expr(PHP_INT_MAX), (float)PHP_INT_MAX); // Depending on your platform, this might or might not pass
        $this->checkQueryResult(r\expr('foo'), 'foo');
        $this->checkQueryResult(r\expr(array('foo' => 'val')), array('foo' => 'val'));
        $this->checkQueryResult(r\expr(array('foo' => 7)), array('foo' => 7.0));
        $this->checkQueryResult(r\expr(array('foo' => null)), array('foo' => null));
        $this->checkQueryResult(r\expr(array('foo' => true)), array('foo' => true));
        $this->checkQueryResult(r\expr(array(1, 2, 3)), array(1.0, 2.0, 3.0));
        $this->checkQueryResult(r\expr(array(1, 'foo', true, null)), array(1.0, 'foo', true, null));
        
        // Special cases where we have to use manual Datum objects
        $this->checkQueryResult(new r\ArrayDatum(array()), array());
        $this->checkQueryResult(new r\ObjectDatum(array()), array());
        $this->checkQueryResult(new r\ObjectDatum(array(4 => new r\StringDatum('a'))), array(4 => 'a'));
        $this->checkQueryResult(new r\ObjectDatum(array('4' => new r\StringDatum('a'))), array('4' => 'a'));
    }
}

?>
