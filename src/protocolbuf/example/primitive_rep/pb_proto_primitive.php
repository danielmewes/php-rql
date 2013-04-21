<?php
class AddressBook_PhoneType extends PBEnum
{
  const MOBILE  = 0;
  const HOME  = 1;
  const WORK  = 2;
}
class AddressBook extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    $this->fields["2"] = "PBString";
    $this->values["2"] = array();
    $this->fields["3"] = "AddressBook_PhoneType";
    $this->values["3"] = array();
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
  function type($offset)
  {
    $v = $this->_get_arr_value("3", $offset);
    return $v->get_value();
  }
  function append_type($value)
  {
    $v = $this->_add_arr_value("3");
    $v->set_value($value);
  }
  function type_size()
  {
    return $this->_get_arr_size("3");
  }
}
?>