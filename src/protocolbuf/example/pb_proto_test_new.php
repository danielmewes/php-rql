<?php
class Person_PhoneType extends PBEnum
{
  const MOBILE  = 0;
  const HOME  = 1;
  const WORK  = 2;
}
class Person_PhoneNumber extends PBMessage
{
  var $wired_type = PBMessage::WIRED_STRING;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->fields["2"] = "Person_PhoneType";
    $this->values["2"] = "";
    $this->values["2"] = new Person_PhoneType();
    $this->values["2"]->value = Person_PhoneType::HOME;
  }
  function number()
  {
    return $this->_get_value("1");
  }
  function set_number($value)
  {
    return $this->_set_value("1", $value);
  }
  function type()
  {
    return $this->_get_value("2");
  }
  function set_type($value)
  {
    return $this->_set_value("2", $value);
  }
}
class Person extends PBMessage
{
  var $wired_type = PBMessage::WIRED_STRING;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "PBString";
    $this->values["1"] = "";
    $this->fields["2"] = "PBInt";
    $this->values["2"] = "";
    $this->fields["3"] = "PBString";
    $this->values["3"] = "";
    $this->fields["4"] = "Person_PhoneNumber";
    $this->values["4"] = array();
    $this->fields["5"] = "PBString";
    $this->values["5"] = "";
  }
  function name()
  {
    return $this->_get_value("1");
  }
  function set_name($value)
  {
    return $this->_set_value("1", $value);
  }
  function id()
  {
    return $this->_get_value("2");
  }
  function set_id($value)
  {
    return $this->_set_value("2", $value);
  }
  function email()
  {
    return $this->_get_value("3");
  }
  function set_email($value)
  {
    return $this->_set_value("3", $value);
  }
  function phone($offset)
  {
    return $this->_get_arr_value("4", $offset);
  }
  function add_phone()
  {
    return $this->_add_arr_value("4");
  }
  function remove_phone($offset)
  {
    $this->_rem_arr_value("4", $offset);
  }
  function phone_size()
  {
    return $this->_get_arr_size("4");
  }
  function surname()
  {
    return $this->_get_value("5");
  }
  function set_surname($value)
  {
    return $this->_set_value("5", $value);
  }
}
class AddressBook extends PBMessage
{
  var $wired_type = PBMessage::WIRED_STRING;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["1"] = "Person";
    $this->values["1"] = array();
  }
  function person($offset)
  {
    return $this->_get_arr_value("1", $offset);
  }
  function add_person()
  {
    return $this->_add_arr_value("1");
  }
  function remove_person($offset)
  {
    $this->_rem_arr_value("1", $offset);
  }
  function person_size()
  {
    return $this->_get_arr_size("1");
  }
}
class Test extends PBMessage
{
  var $wired_type = PBMessage::WIRED_STRING;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["2"] = "PBString";
    $this->values["2"] = array();
  }
  function person($offset)
  {
    $v = $this->_get_arr_value("2", $offset);
    return $v->get_value();
  }
  function append_person($value)
  {
    $v = $this->_add_arr_value("2");
    $v->set_value($value);
  }
  function person_size()
  {
    return $this->_get_arr_size("2");
  }
}
?>