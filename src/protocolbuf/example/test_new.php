<?php
// first include pb_message
require_once('../message/pb_message.php');

// include the generated file
require_once('./pb_proto_test_new.php');



// generate message with the new definition with surname
// now just test the classes
$book = new AddressBook();
$person = $book->add_person();
$person->set_name('Nikolai');
$person = $book->add_person();
$person->set_name('Kordulla');
$person->set_surname('MySurname');

$phone_number = $person->add_phone();
$phone_number->set_number('0711');
$phone_number->set_type(Person_PhoneType::WORK);

$phone_number = $person->add_phone();
$phone_number->set_number('0171');
$phone_number->set_type(Person_PhoneType::MOBILE);

$phone_number = $person->add_phone();
$phone_number->set_number('030');

// serialize
$string = $book->SerializeToString();

// write it to disk
file_put_contents('test.pb', $string);


?>