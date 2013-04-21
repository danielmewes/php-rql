php-rql
=======

A PHP client driver for the RethinkDB query language (RQL).

PHP-RQL is licensed under the terms of the Apache License 2.0 http://www.apache.org/licenses/LICENSE-2.0

Overview
--------

PHP-RQL provides a driver to access RethinkDB databases from PHP code.

It currently implements the query language and wire protocol of RethinkDB 1.4.x

The API of the driver is generally close to that of the official RethinkDB JavaScript driver.

Preliminary documentation is available at: http://dmewes.com/~daniel/php-rql-api/#ph
(Except for the PHP examples, the documentation is intellectual property of RethinkDB)

Requirements
------------

PHP 5.3

Installing
----------

* Download the ZIP file of this repository: https://github.com/danielmewes/php-rql/archive/master.zip
or clone it using git.
* Unpack it
* Copy the contents of the src directory (folders protocolbuf and rdb) into the path of your PHP project.

Example
-------

    <?php
        // Load the driver
        require_once("rdb/rdb.php");

        // Connect to localhost
        $conn = r\connect('localhost');

        // Create a test table
        r\db("test")->tableCreate("tablePhpTest")->run($conn);

        // Insert a document
        $document = array('someKey' => 'someValue');
        $result = r\db("test")->table("tablePhpTest")->insert($document)->run($conn);
        echo "Insert: $result\n";

        // How many documents are in the table?
        $result = r\db("test")->table("tablePhpTest")->count()->run($conn);
        echo "Count: $result\n";

        // List the someKey values of the documents in the table (using a mapping-function)
        $result = r\db("test")->table("tablePhpTest")->map(function($x) {
                return $x('someKey');
            })->run($conn);
            
        foreach ($result as $doc) {
            echo "Doc: $doc\n";
        }
        
        // Delete the test table
        r\db("test")->tableDrop("tablePhpTest")->run($conn);
    ?>

Attributions
------------
PHP-RQL uses pb4php http://code.google.com/p/pb4php/ by Nikolai Kordulla.
A patch for support of doubles in protocol buffers comes from Dmitry Vorobyev https://code.google.com/p/pb4php/issues/detail?id=16
The documentation system and most of the API documentation (except for PHP-specific parts) are from RethinkDB http://rethinkdb.com , as is the protocol buffer specification used.

