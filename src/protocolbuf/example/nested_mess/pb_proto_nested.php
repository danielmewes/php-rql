<?php
class TPublic extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->fields["2"] = "PBString";
    $this->values["2"] = "";
    $this->fields["3"] = "PBString";
    $this->values["3"] = "";
    $this->fields["4"] = "PBString";
    $this->values["4"] = "";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
    $this->fields["6"] = "PBString";
    $this->values["6"] = "";
  }
  function title()
  {
    return $this->_get_value("1");
  }
  function set_title($value)
  {
    return $this->_set_value("1", $value);
  }
  function url()
  {
    return $this->_get_value("2");
  }
  function set_url($value)
  {
    return $this->_set_value("2", $value);
  }
  function feedurl()
  {
    return $this->_get_value("3");
  }
  function set_feedurl($value)
  {
    return $this->_set_value("3", $value);
  }
  function pictureurl()
  {
    return $this->_get_value("4");
  }
  function set_pictureurl($value)
  {
    return $this->_set_value("4", $value);
  }
  function author()
  {
    return $this->_get_value("5");
  }
  function set_author($value)
  {
    return $this->_set_value("5", $value);
  }
  function description()
  {
    return $this->_get_value("6");
  }
  function set_description($value)
  {
    return $this->_set_value("6", $value);
  }
}
class RssEntry extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "PBInt";
    $this->values["1"] = "";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->fields["3"] = "PBInt";
    $this->values["3"] = "";
    $this->fields["4"] = "PBString";
    $this->values["4"] = "";
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
    $this->fields["6"] = "PBString";
    $this->values["6"] = "";
    $this->fields["7"] = "PBString";
    $this->values["7"] = "";
    $this->fields["8"] = "PBString";
    $this->values["8"] = "";
    $this->fields["9"] = "PBString";
    $this->values["9"] = "";
    $this->fields["10"] = "PBString";
    $this->values["10"] = "";
    $this->fields["12"] = "TPublic";
    $this->values["12"] = "";
  }
  function publisheddate()
  {
    return $this->_get_value("1");
  }
  function set_publisheddate($value)
  {
    return $this->_set_value("1", $value);
  }
  function indexeddate()
  {
    return $this->_get_value("2");
  }
  function set_indexeddate($value)
  {
    return $this->_set_value("2", $value);
  }
  function updateddate()
  {
    return $this->_get_value("3");
  }
  function set_updateddate($value)
  {
    return $this->_set_value("3", $value);
  }
  function title()
  {
    return $this->_get_value("4");
  }
  function set_title($value)
  {
    return $this->_set_value("4", $value);
  }
  function author()
  {
    return $this->_get_value("5");
  }
  function set_author($value)
  {
    return $this->_set_value("5", $value);
  }
  function guid()
  {
    return $this->_get_value("6");
  }
  function set_guid($value)
  {
    return $this->_set_value("6", $value);
  }
  function url()
  {
    return $this->_get_value("7");
  }
  function set_url($value)
  {
    return $this->_set_value("7", $value);
  }
  function description()
  {
    return $this->_get_value("8");
  }
  function set_description($value)
  {
    return $this->_set_value("8", $value);
  }
  function extractedcontent()
  {
    return $this->_get_value("9");
  }
  function set_extractedcontent($value)
  {
    return $this->_set_value("9", $value);
  }
  function html()
  {
    return $this->_get_value("10");
  }
  function set_html($value)
  {
    return $this->_set_value("10", $value);
  }
  function source()
  {
    return $this->_get_value("12");
  }
  function set_source($value)
  {
    return $this->_set_value("12", $value);
  }
}
class Entry_Assign extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "PBString";
    $this->values["1"] = array();
    $this->fields["2"] = "PBString";
    $this->values["2"] = array();
  }
  function nottake($offset)
  {
    $v = $this->_get_arr_value("1", $offset);
    return $v->get_value();
  }
  function append_nottake($value)
  {
    $v = $this->_add_arr_value("1");
    $v->set_value($value);
  }
  function set_nottake($index, $value)
  {
    $v = new $this->fields["1"]();
    $v->set_value($value);
    $this->_set_arr_value("1", $index, $v);
  }
  function remove_last_nottake()
  {
    $this->_remove_last_arr_value("1");
  }
  function nottake_size()
  {
    return $this->_get_arr_size("1");
  }
  function take($offset)
  {
    $v = $this->_get_arr_value("2", $offset);
    return $v->get_value();
  }
  function append_take($value)
  {
    $v = $this->_add_arr_value("2");
    $v->set_value($value);
  }
  function set_take($index, $value)
  {
    $v = new $this->fields["2"]();
    $v->set_value($value);
    $this->_set_arr_value("2", $index, $v);
  }
  function remove_last_take()
  {
    $this->_remove_last_arr_value("2");
  }
  function take_size()
  {
    return $this->_get_arr_size("2");
  }
}
class Entry extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["4"] = "RssEntry";
    $this->values["4"] = "";
    $this->fields["13"] = "Entry_Assign";
    $this->values["13"] = "";
  }
  function rssentry()
  {
    return $this->_get_value("4");
  }
  function set_rssentry($value)
  {
    return $this->_set_value("4", $value);
  }
  function assign()
  {
    return $this->_get_value("13");
  }
  function set_assign($value)
  {
    return $this->_set_value("13", $value);
  }
}
class SomeOtherMessage extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "Entry_Assign";
    $this->values["1"] = "";
  }
  function result()
  {
    return $this->_get_value("1");
  }
  function set_result($value)
  {
    return $this->_set_value("1", $value);
  }
}
?>