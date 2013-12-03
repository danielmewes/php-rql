<?php

class CursorTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Huge');

        $doc = array('key' => str_repeat("var", 1000));
        $docs = array_fill(0, 5000, $doc);

        // Test 1: Retrieve only the first 100 results. Then delete the cursor. This should trigger a stop message.
        $cursor = r\db('Huge')->table('t5000')->without('id')->run($this->conn);
        $i = 0;
        foreach($cursor as $val) {
            if (!$this->compareArrays($doc, $val->toNative())) echo "Read wrong value.\n";
            if ($i++ >= 100) break;
        }
        unset($cursor);

        // Test 2: Call the cursor's close() method. The cursor should not return any more rows.
        $cursor = r\db('Huge')->table('t5000')->without('id')->run($this->conn);
        $cursor->close();
        if (!$this->compareArrays(array(), $cursor->toArray())) echo "Cursor still returned results after it was closed.\n";

        // Test 3: Retrieve all results. This tests paging.
        $this->checkQueryResult(r\db('Huge')->table('t5000')->without('id'), $docs);
    }
}

?>
