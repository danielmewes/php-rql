<?php

class CursorTest extends TestCase
{
    public function run()
    {
        // Prepare a table with 5000 rows
        r\dbCreate('CursorTest')->run($this->conn);
        r\db('CursorTest')->tableCreate('t5000', array('hard_durability' => false, 'cache_size' => 128))->run($this->conn);
        
        $doc = array('key' => 'val');
        $docs = array_fill(0, 5000, $doc);
        $this->checkQueryResult(r\db('CursorTest')->table('t5000')->insert($docs)->without('generated_keys'), array('unchanged' => 0, 'skipped' => 0, 'replaced' => 0, 'inserted' => 5000, 'errors' => 0, 'deleted' => 0));
    
        // Test 1: Retrieve only the first 100 results. Then delete the cursor. This should trigger a stop message.
        $cursor = r\db('CursorTest')->table('t5000')->without('id')->run($this->conn);
        $i = 0;
        foreach($cursor as $val) {
            if (count(array_diff($val->toNative(), $doc)) > 0) echo "Read wrong value.\n";
            if ($i++ >= 100) break;
        }
        unset($cursor);
        
        // Test 1: Retrieve all results. This tests paging.
        $this->checkQueryResult(r\db('CursorTest')->table('t5000')->without('id'), $docs);
        
        r\dbDrop('CursorTest')->run($this->conn);
    }
}

?>
