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
}
class Datum extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "\I_PBEnum";
    $this->values["1"] = "";
    $this->fields["2"] = "\I_PBBool";
    $this->values["2"] = "";
    $this->fields["3"] = "\PBDouble";
    $this->values["3"] = "";
    $this->fields["4"] = "\I_PBString";
    $this->values["4"] = "";
    $this->fields["5"] = "\\r\\pb\\Datum";
    $this->values["5"] = array();
    $this->fields["6"] = "\\r\\pb\\Datum_AssocPair";
    $this->values["6"] = array();
  }
  function type()
  {
    return $this->_get_value("1");
  }
  function set_type($value)
  {
    return $this->_set_value("1", $value);
  }
  function r_bool()
  {
    return $this->_get_value("2");
  }
  function set_r_bool($value)
  {
    return $this->_set_value("2", $value);
  }
  function r_num()
  {
    return $this->_get_value("3");
  }
  function set_r_num($value)
  {
    return $this->_set_value("3", $value);
  }
  function r_str()
  {
    return $this->_get_value("4");
  }
  function set_r_str($value)
  {
    return $this->_set_value("4", $value);
  }
  function r_array($offset)
  {
    return $this->_get_arr_value("5", $offset);
  }
  function add_r_array()
  {
    return $this->_add_arr_value("5");
  }
  function set_r_array($index, $value)
  {
    $this->_set_arr_value("5", $index, $value);
  }
  function remove_last_r_array()
  {
    $this->_remove_last_arr_value("5");
  }
  function r_array_size()
  {
    return $this->_get_arr_size("5");
  }
  function r_object($offset)
  {
    return $this->_get_arr_value("6", $offset);
  }
  function add_r_object()
  {
    return $this->_add_arr_value("6");
  }
  function set_r_object($index, $value)
  {
    $this->_set_arr_value("6", $index, $value);
  }
  function remove_last_r_object()
  {
    $this->_remove_last_arr_value("6");
  }
  function r_object_size()
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
}
class Datum_AssocPair extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "\I_PBString";
    $this->values["1"] = "";
    $this->fields["2"] = "\\r\\pb\\Datum";
    $this->values["2"] = "";
  }
  function key()
  {
    return $this->_get_value("1");
  }
  function set_key($value)
  {
    return $this->_set_value("1", $value);
  }
  function val()
  {
    return $this->_get_value("2");
  }
  function set_val($value)
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
    $this->fields["1"] = "\I_PBEnum";
    $this->values["1"] = "";
    $this->fields["2"] = "\\r\\pb\\Datum";
    $this->values["2"] = "";
    $this->fields["3"] = "\\r\\pb\\Term";
    $this->values["3"] = array();
    $this->fields["4"] = "\\r\\pb\\Term_AssocPair";
    $this->values["4"] = array();
  }
  function type()
  {
    return $this->_get_value("1");
  }
  function set_type($value)
  {
    return $this->_set_value("1", $value);
  }
  function datum()
  {
    return $this->_get_value("2");
  }
  function set_datum($value)
  {
    return $this->_set_value("2", $value);
  }
  function args($offset)
  {
    return $this->_get_arr_value("3", $offset);
  }
  function add_args()
  {
    return $this->_add_arr_value("3");
  }
  function set_args($index, $value)
  {
    $this->_set_arr_value("3", $index, $value);
  }
  function remove_last_args()
  {
    $this->_remove_last_arr_value("3");
  }
  function args_size()
  {
    return $this->_get_arr_size("3");
  }
  function optargs($offset)
  {
    return $this->_get_arr_value("4", $offset);
  }
  function add_optargs()
  {
    return $this->_add_arr_value("4");
  }
  function set_optargs($index, $value)
  {
    $this->_set_arr_value("4", $index, $value);
  }
  function remove_last_optargs()
  {
    $this->_remove_last_arr_value("4");
  }
  function optargs_size()
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
  const PB_SLICE  = 30;
  const PB_SKIP  = 70;
  const PB_LIMIT  = 71;
  const PB_GETATTR  = 31;
  const PB_CONTAINS  = 32;
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
  const PB_UNION  = 44;
  const PB_NTH  = 45;
  const PB_GROUPED_MAP_REDUCE  = 46;
  const PB_GROUPBY  = 47;
  const PB_INNER_JOIN  = 48;
  const PB_OUTER_JOIN  = 49;
  const PB_EQ_JOIN  = 50;
  const PB_ZIP  = 72;
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
  const PB_INDEX_CREATE  = 75;
  const PB_INDEX_DROP  = 76;
  const PB_INDEX_LIST  = 77;
  const PB_FUNCALL  = 64;
  const PB_BRANCH  = 65;
  const PB_ANY  = 66;
  const PB_ALL  = 67;
  const PB_FOREACH  = 68;
  const PB_FUNC  = 69;
  const PB_ASC  = 73;
  const PB_DESC  = 74;
  const PB_INFO  = 79;
}
class Term_AssocPair extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "\I_PBString";
    $this->values["1"] = "";
    $this->fields["2"] = "\\r\\pb\\Term";
    $this->values["2"] = "";
  }
  function key()
  {
    return $this->_get_value("1");
  }
  function set_key($value)
  {
    return $this->_set_value("1", $value);
  }
  function val()
  {
    return $this->_get_value("2");
  }
  function set_val($value)
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
    $this->fields["1"] = "\I_PBEnum";
    $this->values["1"] = "";
    $this->fields["2"] = "\\r\\pb\\Term";
    $this->values["2"] = "";
    $this->fields["3"] = "\I_PBInt";
    $this->values["3"] = "";
    $this->fields["6"] = "\\r\\pb\\Query_AssocPair";
    $this->values["6"] = array();
  }
  function type()
  {
    return $this->_get_value("1");
  }
  function set_type($value)
  {
    return $this->_set_value("1", $value);
  }
  function query()
  {
    return $this->_get_value("2");
  }
  function set_query($value)
  {
    return $this->_set_value("2", $value);
  }
  function token()
  {
    return $this->_get_value("3");
  }
  function set_token($value)
  {
    return $this->_set_value("3", $value);
  }
  function global_optargs($offset)
  {
    return $this->_get_arr_value("6", $offset);
  }
  function add_global_optargs()
  {
    return $this->_add_arr_value("6");
  }
  function set_global_optargs($index, $value)
  {
    $this->_set_arr_value("6", $index, $value);
  }
  function remove_last_global_optargs()
  {
    $this->_remove_last_arr_value("6");
  }
  function global_optargs_size()
  {
    return $this->_get_arr_size("6");
  }
}
class Query_QueryType extends \PBEnum
{
  const PB_START  = 1;
  const PB_CONTINUE  = 2;
  const PB_STOP  = 3;
}
class Query_AssocPair extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "\I_PBString";
    $this->values["1"] = "";
    $this->fields["2"] = "\\r\\pb\\Term";
    $this->values["2"] = "";
  }
  function key()
  {
    return $this->_get_value("1");
  }
  function set_key($value)
  {
    return $this->_set_value("1", $value);
  }
  function val()
  {
    return $this->_get_value("2");
  }
  function set_val($value)
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
    $this->fields["1"] = "\I_PBEnum";
    $this->values["1"] = "";
    $this->fields["2"] = "\I_PBInt";
    $this->values["2"] = "";
    $this->fields["3"] = "\I_PBString";
    $this->values["3"] = "";
  }
  function type()
  {
    return $this->_get_value("1");
  }
  function set_type($value)
  {
    return $this->_set_value("1", $value);
  }
  function pos()
  {
    return $this->_get_value("2");
  }
  function set_pos($value)
  {
    return $this->_set_value("2", $value);
  }
  function opt()
  {
    return $this->_get_value("3");
  }
  function set_opt($value)
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
    $this->fields["1"] = "\\r\\pb\\Frame";
    $this->values["1"] = array();
  }
  function frames($offset)
  {
    return $this->_get_arr_value("1", $offset);
  }
  function add_frames()
  {
    return $this->_add_arr_value("1");
  }
  function set_frames($index, $value)
  {
    $this->_set_arr_value("1", $index, $value);
  }
  function remove_last_frames()
  {
    $this->_remove_last_arr_value("1");
  }
  function frames_size()
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
    $this->fields["1"] = "\I_PBEnum";
    $this->values["1"] = "";
    $this->fields["2"] = "\I_PBInt";
    $this->values["2"] = "";
    $this->fields["3"] = "\\r\\pb\\Datum";
    $this->values["3"] = array();
    $this->fields["4"] = "\\r\\pb\\Backtrace";
    $this->values["4"] = "";
  }
  function type()
  {
    return $this->_get_value("1");
  }
  function set_type($value)
  {
    return $this->_set_value("1", $value);
  }
  function token()
  {
    return $this->_get_value("2");
  }
  function set_token($value)
  {
    return $this->_set_value("2", $value);
  }
  function response($offset)
  {
    return $this->_get_arr_value("3", $offset);
  }
  function add_response()
  {
    return $this->_add_arr_value("3");
  }
  function set_response($index, $value)
  {
    $this->_set_arr_value("3", $index, $value);
  }
  function remove_last_response()
  {
    $this->_remove_last_arr_value("3");
  }
  function response_size()
  {
    return $this->_get_arr_size("3");
  }
  function backtrace()
  {
    return $this->_get_value("4");
  }
  function set_backtrace($value)
  {
    return $this->_set_value("4", $value);
  }
}
class Response_ResponseType extends \PBEnum
{
  const PB_SUCCESS_ATOM  = 1;
  const PB_SUCCESS_SEQUENCE  = 2;
  const PB_SUCCESS_PARTIAL  = 3;
  const PB_CLIENT_ERROR  = 16;
  const PB_COMPILE_ERROR  = 17;
  const PB_RUNTIME_ERROR  = 18;
}
?>