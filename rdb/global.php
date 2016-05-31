<?php

namespace r;

use r\DatumConverter;
use r\Exceptions\RqlDriverError;
use r\Ordering\Asc;
use r\Ordering\Desc;
use r\Queries\Control\Args;
use r\Queries\Control\Branch;
use r\Queries\Control\Error;
use r\Queries\Control\Http;
use r\Queries\Control\Range;
use r\Queries\Control\RDo;
use r\Queries\Dates\April;
use r\Queries\Dates\August;
use r\Queries\Dates\December;
use r\Queries\Dates\EpochTime;
use r\Queries\Dates\February;
use r\Queries\Dates\Friday;
use r\Queries\Dates\Iso8601;
use r\Queries\Dates\January;
use r\Queries\Dates\July;
use r\Queries\Dates\June;
use r\Queries\Dates\March;
use r\Queries\Dates\May;
use r\Queries\Dates\Monday;
use r\Queries\Dates\November;
use r\Queries\Dates\Now;
use r\Queries\Dates\October;
use r\Queries\Dates\Saturday;
use r\Queries\Dates\September;
use r\Queries\Dates\Sunday;
use r\Queries\Dates\Thursday;
use r\Queries\Dates\Time;
use r\Queries\Dates\Tuesday;
use r\Queries\Dates\Wednesday;
use r\Queries\Dbs\Db;
use r\Queries\Dbs\DbCreate;
use r\Queries\Dbs\DbDrop;
use r\Queries\Dbs\DbList;
use r\Queries\Geo\Circle;
use r\Queries\Geo\Distance;
use r\Queries\Geo\GeoJSON;
use r\Queries\Geo\Intersects;
use r\Queries\Geo\Line;
use r\Queries\Geo\Point;
use r\Queries\Geo\Polygon;
use r\Queries\Manipulation\GetField;
use r\Queries\Math\Add;
use r\Queries\Math\Ceil;
use r\Queries\Math\Div;
use r\Queries\Math\Eq;
use r\Queries\Math\Floor;
use r\Queries\Math\Gt;
use r\Queries\Math\Le;
use r\Queries\Math\Lt;
use r\Queries\Math\Mod;
use r\Queries\Math\Mul;
use r\Queries\Math\Ne;
use r\Queries\Math\Not;
use r\Queries\Math\RAnd;
use r\Queries\Math\ROr;
use r\Queries\Math\Random;
use r\Queries\Math\Round;
use r\Queries\Math\Sub;
use r\Queries\Misc\Maxval;
use r\Queries\Misc\Minval;
use r\Queries\Misc\Uuid;
use r\Queries\Math\Ge;
use r\Queries\Tables\Table;
use r\Queries\Tables\TableCreate;
use r\Queries\Tables\TableDrop;
use r\Queries\Tables\TableList;
use r\Queries\Transformations\MapMultiple;
use r\Queries\Transformations\Union;
use r\ValuedQuery\ImplicitVar;
use r\Queries\Control\Js;
use r\ValuedQuery\Json;
use r\ValuedQuery\Literal;
use r\ValuedQuery\RObject;

// ------------- Global functions in namespace r -------------

function connect($optsOrHost = null, $port = null, $db = null, $apiKey = null, $timeout = null)
{
    return new Connection($optsOrHost, $port, $db, $apiKey, $timeout);
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

function table($tableName, $useOutdatedOrOpts = null)
{
    return new Table(null, $tableName, $useOutdatedOrOpts);
}

function tableCreate($tableName, $options = null)
{
    return new TableCreate(null, $tableName, $options);
}
function tableDrop($tableName)
{
    return new TableDrop(null, $tableName);
}
function tableList()
{
    return new TableList(null);
}

function rDo($args, $inExpr)
{
    return new RDo($args, $inExpr);
}

function args($args)
{
    return new Args($args);
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

function expr($obj)
{
    if ((is_object($obj) && is_subclass_of($obj, "\\r\\Query"))) {
        return $obj;
    }

    $dc = new DatumConverter;
    return $dc->nativeToDatum($obj);
}

function binary($str)
{
    $encodedStr = base64_encode($str);
    if ($encodedStr === false) {
        throw new RqlDriverError("Failed to Base64 encode '" . $str . "'");
    }
    $pseudo = array('$reql_type$' => 'BINARY', 'data' => $encodedStr);

    $dc = new DatumConverter;
    return $dc->nativeToDatum($pseudo);
}

function desc($attribute)
{
    return new Desc($attribute);
}

function asc($attribute)
{
    return new Asc($attribute);
}

function json($json)
{
    return new Json($json);
}

function http($url, $opts = null)
{
    return new Http($url, $opts);
}

function rObject($object)
{
    return new RObject($object);
}

// r\literal can accept 0 or 1 arguments
function literal()
{
    if (func_num_args() == 0) {
        return new Literal();
    } else {
        return new Literal(func_get_arg(0));
    }
}

function add($expr1, $expr2)
{
    return new Add($expr1, $expr2);
}
function sub($expr1, $expr2)
{
    return new Sub($expr1, $expr2);
}
function mul($expr1, $expr2)
{
    return new Mul($expr1, $expr2);
}
function div($expr1, $expr2)
{
    return new Div($expr1, $expr2);
}
function mod($expr1, $expr2)
{
    return new Mod($expr1, $expr2);
}

function rAnd($expr1, $expr2)
{
    return new RAnd($expr1, $expr2);
}
function rOr($expr1, $expr2)
{
    return new ROr($expr1, $expr2);
}

function eq($expr1, $expr2)
{
    return new Eq($expr1, $expr2);
}
function ne($expr1, $expr2)
{
    return new Ne($expr1, $expr2);
}
function gt($expr1, $expr2)
{
    return new Gt($expr1, $expr2);
}
function ge($expr1, $expr2)
{
    return new Ge($expr1, $expr2);
}
function lt($expr1, $expr2)
{
    return new Lt($expr1, $expr2);
}
function le($expr1, $expr2)
{
    return new Le($expr1, $expr2);
}

function not($expr)
{
    return new Not($expr);
}

function random($left = null, $right = null, $opts = null)
{
    return new Random($left, $right, $opts);
}

function now()
{
    return new Now();
}

function time($year, $month, $day, $hourOrTimezone = null, $minute = null, $second = null, $timezone = null)
{
    return new Time($year, $month, $day, $hourOrTimezone, $minute, $second, $timezone);
}

function epochTime($epochTime)
{
    return new EpochTime($epochTime);
}

function iso8601($iso8601Date, $opts = null)
{
    return new Iso8601($iso8601Date, $opts);
}

function monday()
{
    return new Monday();
}
function tuesday()
{
    return new Tuesday();
}
function wednesday()
{
    return new Wednesday();
}
function thursday()
{
    return new Thursday();
}
function friday()
{
    return new Friday();
}
function saturday()
{
    return new Saturday();
}
function sunday()
{
    return new Sunday();
}

function january()
{
    return new January();
}
function february()
{
    return new February();
}
function march()
{
    return new March();
}
function april()
{
    return new April();
}
function may()
{
    return new May();
}
function june()
{
    return new June();
}
function july()
{
    return new July();
}
function august()
{
    return new August();
}
function september()
{
    return new September();
}
function october()
{
    return new October();
}
function november()
{
    return new November();
}
function december()
{
    return new December();
}

function geoJSON($geojson)
{
    return new GeoJSON($geojson);
}

function point($lat, $lon)
{
    return new Point($lat, $lon);
}

function line($points)
{
    return new Line($points);
}

function polygon($points)
{
    return new Polygon($points);
}

function circle($center, $radius, $opts = null)
{
    return new Circle($center, $radius, $opts);
}

function intersects($g1, $g2)
{
    return new Intersects($g1, $g2);
}

function distance($g1, $g2, $opts = null)
{
    return new Distance($g1, $g2, $opts);
}

function uuid($str = null)
{
    return new Uuid($str);
}

function minval()
{
    return new Minval();
}

function maxval()
{
    return new Maxval();
}

function range($startOrEndValue = null, $endValue = null)
{
    return new Range($startOrEndValue, $endValue);
}

function mapMultiple($sequences, $mappingFunction)
{
    if (!is_array($sequences)) {
        $sequences = array($sequences);
    }
    if (sizeof($sequences) < 1) {
        throw new RqlDriverError("At least one sequence must be passed into r\mapMultiple.");
    }
    return new MapMultiple($sequences[0], array_slice($sequences, 1), $mappingFunction);
}

function union($sequence, $otherSequence, $opts = null)
{
    return new Union($sequence, $otherSequence, $opts);
}

function ceil($value)
{
    return new Ceil($value);
}

function floor($value)
{
    return new Floor($value);
}

function round($value)
{
    return new Round($value);
}

function systemInfo()
{
    return "PHP-RQL Version: " . PHP_RQL_VERSION . "\n";
}
