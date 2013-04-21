<?php namespace r;

require_once("misc.php");
require_once("connection.php");

// ------------- Global functions in namespace r -------------

function connect($host, $port = 28015, $db = null)
{
    return new Connection($host, $port, $db);
}

function db($dbName)
{
    return new Db($dbName);
}

function dbCreate($dbName)
{
    return new DbCreate($dbName);
}

function dbDrop($dbName)
{
    return new DbDrop($dbName);
}

function dbList()
{
    return new DbList();
}

function table($tableName, $useOutdated = null)
{
    return new Table(null, $tableName, $useOutdated);
}

function count()
{
    $object = array('COUNT' => new BoolDatum(true));
    return new MakeObject($object);
}

function sum($attribute)
{
    $object = array('SUM' => new StringDatum($attribute));
    return new MakeObject($object);
}

function avg($attribute)
{
    $object = array('AVG' => new StringDatum($attribute));
    return new MakeObject($object);
}

function rDo($args, $inExpr)
{
    return new RDo($args, $inExpr);
}

function branch(Query $test, $trueBranch, $falseBranch)
{
    return new Branch($test, $trueBranch, $falseBranch);
}

function row($attribute = null)
{
    if (isset($attribute)) {
        // A shortcut to do row()($attribute)
        return new Getattr(new ImplicitVar(), $attribute);
    } else {
        return new ImplicitVar();
    }
}

function js($code)
{
    return new Js($code);
}

function error($message)
{
    return new Error($message);
}

function expr($obj) {
    return nativeToDatum($obj);
}

function desc($attribute) {
    return new Desc($attribute);
}

function asc($attribute) {
    return new Asc($attribute);
}

?>
