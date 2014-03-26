<?php namespace r;

require_once("misc.php");
require_once("connection.php");

// ------------- Global functions in namespace r -------------

function connect($host, $port = 28015, $db = null, $apiKey = null, $timeout = null)
{
    return new Connection($host, $port, $db, $apiKey, $timeout);
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

function tableCreate($tableName, $options = null) {
    return new TableCreate(null, $tableName, $options);
}
function tableDrop($tableName) {
    return new TableDrop(null, $tableName);
}
function tableList() {
    return new TableList(null);
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
        return new GetField(new ImplicitVar(), $attribute);
    } else {
        return new ImplicitVar();
    }
}

function js($code, $timeout = null)
{
    return new Js($code, $timeout);
}

function error($message = null)
{
    return new Error($message);
}

function expr($obj) {
    if ((is_object($obj) && is_subclass_of($obj, "\\r\\Query")))
        return $obj;
    return nativeToDatum($obj);
}

function desc($attribute) {
    return new Desc($attribute);
}

function asc($attribute) {
    return new Asc($attribute);
}

function json($json) {
    return new Json($json);
}

function rObject($object)
{
    return new RObject($object);
}

function literal($value) {
    return new Literal($value);
}

function add($expr1, $expr2) {
    return new Add($expr1, $expr2);
}
function sub($expr1, $expr2) {
    return new Sub($expr1, $expr2);
}
function mul($expr1, $expr2) {
    return new Mul($expr1, $expr2);
}
function div($expr1, $expr2) {
    return new Div($expr1, $expr2);
}
function mod($expr1, $expr2) {
    return new Mod($expr1, $expr2);
}

function rAnd($expr1, $expr2) {
    return new RAnd($expr1, $expr2);
}
function rOr($expr1, $expr2) {
    return new ROr($expr1, $expr2);
}

function eq($expr1, $expr2) {
    return new Eq($expr1, $expr2);
}
function ne($expr1, $expr2) {
    return new Ne($expr1, $expr2);
}
function gt($expr1, $expr2) {
    return new Gt($expr1, $expr2);
}
function ge($expr1, $expr2) {
    return new Ge($expr1, $expr2);
}
function lt($expr1, $expr2) {
    return new Lt($expr1, $expr2);
}
function le($expr1, $expr2) {
    return new Le($expr1, $expr2);
}

function not($expr) {
    return new Not($expr);
}

function now() {
    return new Now();
}

function time($year, $month, $day, $hourOrTimezone = null, $minute = null, $second = null, $timezone = null) {
    return new Time($year, $month, $day, $hourOrTimezone, $minute, $second, $timezone);
}

function epochTime($epochTime) {
    return new EpochTime($epochTime);
}

function iso8601($iso8601Date, $opts = null) {
    return new Iso8601($iso8601Date, $opts);
}

function monday() {
    return new Monday();
}
function tuesday() {
    return new Tuesday();
}
function wednesday() {
    return new Wednesday();
}
function thursday() {
    return new Thursday();
}
function friday() {
    return new Friday();
}
function saturday() {
    return new Saturday();
}
function sunday() {
    return new Sunday();
}

function january() {
    return new January();
}
function february() {
    return new February();
}
function march() {
    return new March();
}
function april() {
    return new April();
}
function may() {
    return new May();
}
function june() {
    return new June();
}
function july() {
    return new July();
}
function august() {
    return new August();
}
function september() {
    return new September();
}
function october() {
    return new October();
}
function november() {
    return new November();
}
function december() {
    return new December();
}



?>
