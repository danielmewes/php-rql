<?php namespace r\pb;
class VersionDummy extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
  }
}
class VersionDummy_Version extends \PBEnum
{
  const PB_V0_1  = 0x3f61ba36;
  const PB_V0_2  = 0x723081e1;
}
class Datum extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\I_PBEnum";
    $this->values[1] = null;
    $this->fields[2] = "\\I_PBBool";
    $this->values[2] = null;
    $this->fields[3] = "\\PBDouble";
    $this->values[3] = null;
    $this->fields[4] = "\\I_PBString";
    $this->values[4] = null;
    $this->fields[5] = "\\r\\pb\\Datum";
    $this->values[5] = array();
    $this->fields[6] = "\\r\\pb\\Datum_AssocPair";
    $this->values[6] = array();
  }
  function getType()
  {
    return $this->_get_value("1");
  }
  function setType($value)
  {
    return $this->_set_value("1", $value);
  }
  function getRBool()
  {
    return $this->_get_value("2");
  }
  function setRBool($value)
  {
    return $this->_set_value("2", $value);
  }
  function getRNum()
  {
    return $this->_get_value("3");
  }
  function setRNum($value)
  {
    return $this->_set_value("3", $value);
  }
  function getRStr()
  {
    return $this->_get_value("4");
  }
  function setRStr($value)
  {
    return $this->_set_value("4", $value);
  }
  function getRArrayAt($offset)
  {
    return $this->_get_arr_value("5", $offset);
  }
  function appendRArray($value)
  {
    $this->_set_arr_value("5", $this->_get_arr_size("5"), $value);
  }
  function getRArrayCount()
  {
    return $this->_get_arr_size("5");
  }
  function getRObjectAt($offset)
  {
    return $this->_get_arr_value("6", $offset);
  }
  function appendRObject($value)
  {
    $this->_set_arr_value("6", $this->_get_arr_size("6"), $value);
  }
  function getRObjectCount()
  {
    return $this->_get_arr_size("6");
  }
}
class Datum_DatumType extends \PBEnum
{
  const PB_R_NULL  = 1;
  const PB_R_BOOL  = 2;
  const PB_R_NUM  = 3;
  const PB_R_STR  = 4;
  const PB_R_ARRAY  = 5;
  const PB_R_OBJECT  = 6;
  const PB_R_JSON  = 7;
}
class Datum_AssocPair extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\I_PBString";
    $this->values[1] = null;
    $this->fields[2] = "\\r\\pb\\Datum";
    $this->values[2] = null;
  }
  function getKey()
  {
    return $this->_get_value("1");
  }
  function setKey($value)
  {
    return $this->_set_value("1", $value);
  }
  function getVal()
  {
    return $this->_get_value("2");
  }
  function setVal($value)
  {
    return $this->_set_value("2", $value);
  }
}
class Term extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\I_PBEnum";
    $this->values[1] = null;
    $this->fields[2] = "\\r\\pb\\Datum";
    $this->values[2] = null;
    $this->fields[3] = "\\r\\pb\\Term";
    $this->values[3] = array();
    $this->fields[4] = "\\r\\pb\\Term_AssocPair";
    $this->values[4] = array();
  }
  function getType()
  {
    return $this->_get_value("1");
  }
  function setType($value)
  {
    return $this->_set_value("1", $value);
  }
  function getDatum()
  {
    return $this->_get_value("2");
  }
  function setDatum($value)
  {
    return $this->_set_value("2", $value);
  }
  function getArgsAt($offset)
  {
    return $this->_get_arr_value("3", $offset);
  }
  function appendArgs($value)
  {
    $this->_set_arr_value("3", $this->_get_arr_size("3"), $value);
  }
  function getArgsCount()
  {
    return $this->_get_arr_size("3");
  }
  function getOptargsAt($offset)
  {
    return $this->_get_arr_value("4", $offset);
  }
  function appendOptargs($value)
  {
    $this->_set_arr_value("4", $this->_get_arr_size("4"), $value);
  }
  function getOptargsCount()
  {
    return $this->_get_arr_size("4");
  }
}
class Term_TermType extends \PBEnum
{
  const PB_DATUM  = 1;
  const PB_MAKE_ARRAY  = 2;
  const PB_MAKE_OBJ  = 3;
  const PB_VAR  = 10;
  const PB_JAVASCRIPT  = 11;
  const PB_ERROR  = 12;
  const PB_IMPLICIT_VAR  = 13;
  const PB_DB  = 14;
  const PB_TABLE  = 15;
  const PB_GET  = 16;
  const PB_GET_ALL  = 78;
  const PB_EQ  = 17;
  const PB_NE  = 18;
  const PB_LT  = 19;
  const PB_LE  = 20;
  const PB_GT  = 21;
  const PB_GE  = 22;
  const PB_NOT  = 23;
  const PB_ADD  = 24;
  const PB_SUB  = 25;
  const PB_MUL  = 26;
  const PB_DIV  = 27;
  const PB_MOD  = 28;
  const PB_APPEND  = 29;
  const PB_PREPEND  = 80;
  const PB_DIFFERENCE  = 95;
  const PB_SET_INSERT  = 88;
  const PB_SET_INTERSECTION  = 89;
  const PB_SET_UNION  = 90;
  const PB_SET_DIFFERENCE  = 91;
  const PB_SLICE  = 30;
  const PB_SKIP  = 70;
  const PB_LIMIT  = 71;
  const PB_INDEXES_OF  = 87;
  const PB_CONTAINS  = 93;
  const PB_GET_FIELD  = 31;
  const PB_KEYS  = 94;
  const PB_OBJECT  = 143;
  const PB_HAS_FIELDS  = 32;
  const PB_WITH_FIELDS  = 96;
  const PB_PLUCK  = 33;
  const PB_WITHOUT  = 34;
  const PB_MERGE  = 35;
  const PB_BETWEEN  = 36;
  const PB_REDUCE  = 37;
  const PB_MAP  = 38;
  const PB_FILTER  = 39;
  const PB_CONCATMAP  = 40;
  const PB_ORDERBY  = 41;
  const PB_DISTINCT  = 42;
  const PB_COUNT  = 43;
  const PB_IS_EMPTY  = 86;
  const PB_UNION  = 44;
  const PB_NTH  = 45;
  const PB_GROUPED_MAP_REDUCE  = 46;
  const PB_GROUPBY  = 47;
  const PB_INNER_JOIN  = 48;
  const PB_OUTER_JOIN  = 49;
  const PB_EQ_JOIN  = 50;
  const PB_ZIP  = 72;
  const PB_INSERT_AT  = 82;
  const PB_DELETE_AT  = 83;
  const PB_CHANGE_AT  = 84;
  const PB_SPLICE_AT  = 85;
  const PB_COERCE_TO  = 51;
  const PB_TYPEOF  = 52;
  const PB_UPDATE  = 53;
  const PB_DELETE  = 54;
  const PB_REPLACE  = 55;
  const PB_INSERT  = 56;
  const PB_DB_CREATE  = 57;
  const PB_DB_DROP  = 58;
  const PB_DB_LIST  = 59;
  const PB_TABLE_CREATE  = 60;
  const PB_TABLE_DROP  = 61;
  const PB_TABLE_LIST  = 62;
  const PB_SYNC  = 138;
  const PB_INDEX_CREATE  = 75;
  const PB_INDEX_DROP  = 76;
  const PB_INDEX_LIST  = 77;
  const PB_INDEX_STATUS  = 139;
  const PB_INDEX_WAIT  = 140;
  const PB_FUNCALL  = 64;
  const PB_BRANCH  = 65;
  const PB_ANY  = 66;
  const PB_ALL  = 67;
  const PB_FOREACH  = 68;
  const PB_FUNC  = 69;
  const PB_ASC  = 73;
  const PB_DESC  = 74;
  const PB_INFO  = 79;
  const PB_MATCH  = 97;
  const PB_UPCASE  = 141;
  const PB_DOWNCASE  = 142;
  const PB_SAMPLE  = 81;
  const PB_DEFAULT  = 92;
  const PB_JSON  = 98;
  const PB_ISO8601  = 99;
  const PB_TO_ISO8601  = 100;
  const PB_EPOCH_TIME  = 101;
  const PB_TO_EPOCH_TIME  = 102;
  const PB_NOW  = 103;
  const PB_IN_TIMEZONE  = 104;
  const PB_DURING  = 105;
  const PB_DATE  = 106;
  const PB_TIME_OF_DAY  = 126;
  const PB_TIMEZONE  = 127;
  const PB_YEAR  = 128;
  const PB_MONTH  = 129;
  const PB_DAY  = 130;
  const PB_DAY_OF_WEEK  = 131;
  const PB_DAY_OF_YEAR  = 132;
  const PB_HOURS  = 133;
  const PB_MINUTES  = 134;
  const PB_SECONDS  = 135;
  const PB_TIME  = 136;
  const PB_MONDAY  = 107;
  const PB_TUESDAY  = 108;
  const PB_WEDNESDAY  = 109;
  const PB_THURSDAY  = 110;
  const PB_FRIDAY  = 111;
  const PB_SATURDAY  = 112;
  const PB_SUNDAY  = 113;
  const PB_JANUARY  = 114;
  const PB_FEBRUARY  = 115;
  const PB_MARCH  = 116;
  const PB_APRIL  = 117;
  const PB_MAY  = 118;
  const PB_JUNE  = 119;
  const PB_JULY  = 120;
  const PB_AUGUST  = 121;
  const PB_SEPTEMBER  = 122;
  const PB_OCTOBER  = 123;
  const PB_NOVEMBER  = 124;
  const PB_DECEMBER  = 125;
  const PB_LITERAL  = 137;
  const PB_GROUP  = 144;
  const PB_SUM  = 145;
  const PB_AVG  = 146;
  const PB_MIN  = 147;
  const PB_MAX  = 148;
  const PB_SPLIT  = 149;
  const PB_UNGROUP  = 150;
}
class Term_AssocPair extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\I_PBString";
    $this->values[1] = null;
    $this->fields[2] = "\\r\\pb\\Term";
    $this->values[2] = null;
  }
  function getKey()
  {
    return $this->_get_value("1");
  }
  function setKey($value)
  {
    return $this->_set_value("1", $value);
  }
  function getVal()
  {
    return $this->_get_value("2");
  }
  function setVal($value)
  {
    return $this->_set_value("2", $value);
  }
}
class Query extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\I_PBEnum";
    $this->values[1] = null;
    $this->fields[2] = "\\r\\pb\\Term";
    $this->values[2] = null;
    $this->fields[3] = "\\I_PBInt";
    $this->values[3] = null;
    $this->fields[4] = "\\I_PBBool";
    $this->values[4] = null;
    $this->fields[5] = "\\I_PBBool";
    $this->values[5] = null;
    $this->fields[6] = "\\r\\pb\\Query_AssocPair";
    $this->values[6] = array();
  }
  function getType()
  {
    return $this->_get_value("1");
  }
  function setType($value)
  {
    return $this->_set_value("1", $value);
  }
  function getQuery()
  {
    return $this->_get_value("2");
  }
  function setQuery($value)
  {
    return $this->_set_value("2", $value);
  }
  function getToken()
  {
    return $this->_get_value("3");
  }
  function setToken($value)
  {
    return $this->_set_value("3", $value);
  }
  function getOBSOLETENoreply()
  {
    return $this->_get_value("4");
  }
  function setOBSOLETENoreply($value)
  {
    return $this->_set_value("4", $value);
  }
  function getAcceptsRJson()
  {
    return $this->_get_value("5");
  }
  function setAcceptsRJson($value)
  {
    return $this->_set_value("5", $value);
  }
  function getGlobalOptargsAt($offset)
  {
    return $this->_get_arr_value("6", $offset);
  }
  function appendGlobalOptargs($value)
  {
    $this->_set_arr_value("6", $this->_get_arr_size("6"), $value);
  }
  function getGlobalOptargsCount()
  {
    return $this->_get_arr_size("6");
  }
}
class Query_QueryType extends \PBEnum
{
  const PB_START  = 1;
  const PB_CONTINUE  = 2;
  const PB_STOP  = 3;
  const PB_NOREPLY_WAIT  = 4;
}
class Query_AssocPair extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\I_PBString";
    $this->values[1] = null;
    $this->fields[2] = "\\r\\pb\\Term";
    $this->values[2] = null;
  }
  function getKey()
  {
    return $this->_get_value("1");
  }
  function setKey($value)
  {
    return $this->_set_value("1", $value);
  }
  function getVal()
  {
    return $this->_get_value("2");
  }
  function setVal($value)
  {
    return $this->_set_value("2", $value);
  }
}
class Frame extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\I_PBEnum";
    $this->values[1] = null;
    $this->fields[2] = "\\I_PBInt";
    $this->values[2] = null;
    $this->fields[3] = "\\I_PBString";
    $this->values[3] = null;
  }
  function getType()
  {
    return $this->_get_value("1");
  }
  function setType($value)
  {
    return $this->_set_value("1", $value);
  }
  function getPos()
  {
    return $this->_get_value("2");
  }
  function setPos($value)
  {
    return $this->_set_value("2", $value);
  }
  function getOpt()
  {
    return $this->_get_value("3");
  }
  function setOpt($value)
  {
    return $this->_set_value("3", $value);
  }
}
class Frame_FrameType extends \PBEnum
{
  const PB_POS  = 1;
  const PB_OPT  = 2;
}
class Backtrace extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\r\\pb\\Frame";
    $this->values[1] = array();
  }
  function getFramesAt($offset)
  {
    return $this->_get_arr_value("1", $offset);
  }
  function appendFrames($value)
  {
    $this->_set_arr_value("1", $this->_get_arr_size("1"), $value);
  }
  function getFramesCount()
  {
    return $this->_get_arr_size("1");
  }
}
class Response extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields[1] = "\\I_PBEnum";
    $this->values[1] = null;
    $this->fields[2] = "\\I_PBInt";
    $this->values[2] = null;
    $this->fields[3] = "\\r\\pb\\Datum";
    $this->values[3] = array();
    $this->fields[4] = "\\r\\pb\\Backtrace";
    $this->values[4] = null;
    $this->fields[5] = "\\r\\pb\\Datum";
    $this->values[5] = null;
  }
  function getType()
  {
    return $this->_get_value("1");
  }
  function setType($value)
  {
    return $this->_set_value("1", $value);
  }
  function getToken()
  {
    return $this->_get_value("2");
  }
  function setToken($value)
  {
    return $this->_set_value("2", $value);
  }
  function getResponseAt($offset)
  {
    return $this->_get_arr_value("3", $offset);
  }
  function appendResponse($value)
  {
    $this->_set_arr_value("3", $this->_get_arr_size("3"), $value);
  }
  function getResponseCount()
  {
    return $this->_get_arr_size("3");
  }
  function getBacktrace()
  {
    return $this->_get_value("4");
  }
  function setBacktrace($value)
  {
    return $this->_set_value("4", $value);
  }
  function getProfile()
  {
    return $this->_get_value("5");
  }
  function setProfile($value)
  {
    return $this->_set_value("5", $value);
  }
}
class Response_ResponseType extends \PBEnum
{
  const PB_SUCCESS_ATOM  = 1;
  const PB_SUCCESS_SEQUENCE  = 2;
  const PB_SUCCESS_PARTIAL  = 3;
  const PB_WAIT_COMPLETE  = 4;
  const PB_CLIENT_ERROR  = 16;
  const PB_COMPILE_ERROR  = 17;
  const PB_RUNTIME_ERROR  = 18;
}
?>