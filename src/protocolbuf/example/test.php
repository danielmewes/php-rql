<?php
// EXECUTE test_new.php first 


// first include pb_message
require_once('../message/pb_message.php');

// now read it with the old file
// include the generated file
require_once('./pb_proto_test.php');

$string = file_get_contents('./test.pb');

// Just read it
$book = new AddressBook();
$book->parseFromString($string);

var_dump($book->person_size());
$person = $book->person(0);
var_dump($person->name());
$person = $book->person(1);
var_dump($person->name());
var_dump($person->phone(0)->number());
var_dump($person->phone(0)->type());
var_dump($person->phone(1)->number());
var_dump($person->phone(1)->type());
var_dump($person->phone(2)->number());
var_dump($person->phone(2)->type());
?>