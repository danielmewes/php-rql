<?php
/*
 * Primitive Fields repeated
 */
 
// first include pb_message
require_once('../../message/pb_message.php');

// include the generated file
require_once('./pb_proto_nested.php');


$entry = new Entry();
$assign = new Entry_Assign();
$assign->append_take('Kreuzverhör');
$assign->append_take('hällo');

// remove hällo
$assign->set_take(0, $assign->take(1));
$assign->remove_last_take();
var_dump($assign->take_size());
var_dump($assign->take(0));
exit();

$entry->set_assign($assign);

$string = $entry->SerializeToString();

file_put_contents('nested.pb', $string);

//var_dump($string);

// now test the reading
$entry = new Entry();
$entry->parseFromString($string);

var_dump($entry->assign()->take(0));
//var_dump($entry->assign()->take(1));

?>