<?php

class ValueTest extends TestCase
{
    public function run()
    {
        $useJson = false;
        checkValues:
            if ($useJson) {
                $runOptions = array();
            } else {
                $runOptions = array("noJsonResponse" => true);
            }
            $this->checkQueryResult(r\expr(null), null, $runOptions);
            $this->checkQueryResult(r\expr(true), true, $runOptions);
            $this->checkQueryResult(r\expr(false), false, $runOptions);
            $this->checkQueryResult(r\expr(0.5), 0.5, $runOptions);
            $this->checkQueryResult(r\expr(0), 0.0, $runOptions);
            $this->checkQueryResult(r\expr(-1), -1.0, $runOptions);
            $this->checkQueryResult(r\expr(1), 1.0, $runOptions);
            $this->checkQueryResult(r\expr(PHP_INT_MAX), (float)PHP_INT_MAX, $runOptions); // Depending on your platform, this might or might not pass
            $this->checkQueryResult(r\expr('0.5'), '0.5', $runOptions);
            $this->checkQueryResult(r\expr('foo'), 'foo', $runOptions);
            $this->checkQueryResult(r\expr(array('foo' => 'val')), array('foo' => 'val'), $runOptions);
            $this->checkQueryResult(r\expr(array('foo' => 7)), array('foo' => 7.0), $runOptions);
            $this->checkQueryResult(r\expr(array('foo' => null)), array('foo' => null), $runOptions);
            $this->checkQueryResult(r\expr(array('foo' => true)), array('foo' => true), $runOptions);
            $this->checkQueryResult(r\expr(array(1, 2, 3)), array(1.0, 2.0, 3.0), $runOptions);
            $this->checkQueryResult(r\expr(array(1, 'foo', true, null)), array(1.0, 'foo', true, null), $runOptions);

            // Special cases where we have to use manual Datum objects
            $this->checkQueryResult(new r\ArrayDatum(array()), array(), $runOptions);
            $this->checkQueryResult(new r\ObjectDatum(array()), array(), $runOptions);
            $this->checkQueryResult(new r\ObjectDatum(array(4 => new r\StringDatum('a'))), array(4 => 'a'), $runOptions);
            $this->checkQueryResult(new r\ObjectDatum(array('4' => new r\StringDatum('a'))), array('4' => 'a'), $runOptions);
            $this->checkQueryResult(r\expr(array(new r\ObjectDatum(array()))), array(array()), $runOptions);

        if (!$useJson) {
            $useJson = true;
            // Yippee, goto :-)
            goto checkValues;
        }
    }
}

?>
