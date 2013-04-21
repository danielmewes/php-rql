<?php
/*
 * Primitive Fields repeated
 */
 
// first include pb_message
require_once('../../message/pb_message.php');

// include the generated file
require_once('./pb_proto_primitive.php');

$book = new AddressBook();
$book->append_person("Hello"); 
$book->append_person("Test"); 
$book->append_type(AddressBook_PhoneType::MOBILE);

// in $ p now there is Hello
$p = $book->person(0);
var_dump($p);

$string = $book->SerializeToString();

// now test the reading
$book = new AddressBook();
$book->parseFromString($string);

var_dump($book->person(0));
var_dump($book->person_size());
var_dump($book->person(1));
var_dump($book->type(0) == AddressBook_PhoneType::MOBILE);
?>