<?
/**
 * Including of all files needed to parse messages
 * @author Nikolai Kordulla
 */
require_once(dirname(__FILE__). '/' . 'encoding/pb_base128.php');
require_once(dirname(__FILE__). '/' . 'type/pb_scalar.php');
require_once(dirname(__FILE__). '/' . 'type/pb_enum.php');
require_once(dirname(__FILE__). '/' . 'type/pb_bytes.php');
require_once(dirname(__FILE__). '/' . 'type/pb_string.php');
require_once(dirname(__FILE__). '/' . 'type/pb_int.php');
require_once(dirname(__FILE__). '/' . 'type/pb_bool.php');
require_once(dirname(__FILE__). '/' . 'type/pb_signed_int.php');
require_once(dirname(__FILE__). '/' . 'reader/pb_input_reader.php');
require_once(dirname(__FILE__). '/' . 'reader/pb_input_string_reader.php');
/**
 * Abstract Message class
 * @author Nikolai Kordulla
 */
abstract class PBMessage
{
    const WIRED_VARINT = 0;
    const WIRED_64BIT = 1;
    const WIRED_LENGTH_DELIMITED = 2;
    const WIRED_START_GROUP = 3;
    const WIRED_END_GROUP = 4;
    const WIRED_32BIT = 5;

    var $base128;

    // here are the field types
    var $fields = array();
    // the values for the fields
    var $values = array();

    // type of the class
    var $wired_type = 2;

    // the value of a class
    var $value = null;

    // modus byte or string parse (byte for productive string for better reading and debuging)
    // 1 = byte, 2 = String
    const MODUS = 1;

    // now use pointer for speed improvement
    // pointer to begin
    protected $reader;

    // chunk which the class not understands
    var $chunk = '';

    // variable for Send method
    var $_d_string = '';

    /**
     * Constructor - initialize base128 class
     */
    public function __construct($reader=null)
    {
        $this->reader = $reader;
        $this->value = $this;
        $this->base128 = new base128varint(PBMessage::MODUS);
    }

    /**
     * Get the wired_type and field_type
     * @param $number as decimal
     * @return array wired_type, field_type
     */
    public function get_types($number)
    {
        $binstring = decbin($number);
        $types = array();
        $low = substr($binstring, strlen($binstring) - 3, strlen($binstring));
        $high = substr($binstring,0, strlen($binstring) - 3) . '0000';
        $types['wired'] = bindec($low);
        $types['field'] = bindec($binstring) >> 3;
        return $types;
    }


    /**
     * Encodes a Message
     * @return string the encoded message
     */
    public function SerializeToString($rec=-1)
    {
        $string = '';
        // wired and type
        if ($rec > -1)
        {
            $string .= $this->base128->set_value($rec << 3 | $this->wired_type);
        }

        $stringinner = '';

        foreach ($this->fields as $index => $field)
        {
            if (is_array($this->values[$index]) && count($this->values[$index]) > 0)
            {
                // make serialization for every array
                foreach ($this->values[$index] as $array)
                {
                    $newstring = '';
                    $newstring .= $array->SerializeToString($index);

                    $stringinner .= $newstring;
                }
            }
            else if ($this->values[$index] != null)
            {
                // wired and type
                $newstring = '';
                $newstring .= $this->values[$index]->SerializeToString($index);

                $stringinner .= $newstring;
            }
        }

        $this->_serialize_chunk($stringinner);

        if ($this->wired_type == PBMessage::WIRED_LENGTH_DELIMITED && $rec > -1)
        {
            $stringinner = $this->base128->set_value(strlen($stringinner) / PBMessage::MODUS) . $stringinner;
        }

        return $string . $stringinner;
    }

    /**
     * Serializes the chunk
     * @param String $stringinner - String where to append the chunk
     */
    public function _serialize_chunk(&$stringinner)
    {
        $stringinner .= $this->chunk;
    }

    /**
     * Decodes a Message and Built its things
     *
     * @param message as stream of hex example '1a 03 08 96 01'
     */
    public function ParseFromString($message)
    {
        $this->reader = new PBInputStringReader($message);
        $this->_ParseFromArray();
    }

    /**
     * Internal function
     */
    public function ParseFromArray()
    {
        $this->chunk = '';
        // read the length byte
        $length = $this->reader->next();
        // just take the splice from this array
        $this->_ParseFromArray($length);
    }

    /**
     * Internal function
     */
    private function _ParseFromArray($length=99999999)
    {
        $_begin = $this->reader->get_pointer();
        while ($this->reader->get_pointer() - $_begin < $length)
        {
            $next = $this->reader->next();
            if ($next === false)
                break;

            // now get the message type
            $messtypes = $this->get_types($next);

            // now make method test
            if (!isset($this->fields[$messtypes['field']]))
            {
                // field is unknown so just ignore it
                // throw new Exception('Field ' . $messtypes['field'] . ' not present ');
                if ($messtypes['wired'] == PBMessage::WIRED_LENGTH_DELIMITED)
                {
                    $consume = new PBString($this->reader);
                }
                else if ($messtypes['wired'] == PBMessage::WIRED_VARINT)
                {
                    $consume = new PBInt($this->reader);
                }
                else
                {
                    throw new Exception('I dont understand this wired code:' . $messtypes['wired']);
                }

                // perhaps send a warning out
                // @TODO SEND CHUNK WARNING
                $_oldpointer = $this->reader->get_pointer();
                $consume->ParseFromArray();
                // now add array from _oldpointer to pointer to the chunk array
                $this->chunk .= $this->reader->get_message_from($_oldpointer);
                continue;
            }

            // now array or not
            if (is_array($this->values[$messtypes['field']]))
            {
                $this->values[$messtypes['field']][] = new $this->fields[$messtypes['field']]($this->reader);
                $index = count($this->values[$messtypes['field']]) - 1;
                if ($messtypes['wired'] != $this->values[$messtypes['field']][$index]->wired_type)
                {
                    throw new Exception('Expected type:' . $messtypes['wired'] . ' but had ' . $this->fields[$messtypes['field']]->wired_type);
                }
                $this->values[$messtypes['field']][$index]->ParseFromArray();
            }
            else
            {
                $this->values[$messtypes['field']] = new $this->fields[$messtypes['field']]($this->reader);
                if ($messtypes['wired'] != $this->values[$messtypes['field']]->wired_type)
                {
                    throw new Exception('Expected type:' . $messtypes['wired'] . ' but had ' . $this->fields[$messtypes['field']]->wired_type);
                }
                $this->values[$messtypes['field']]->ParseFromArray();
            }
        }
    }

    /**
     * Add an array value
     * @param int - index of the field
     */
    protected function _add_arr_value($index)
    {
        return $this->values[$index][] = new $this->fields[$index]();
    }

    /**
     * Set an array value - @TODO failure check
     * @param int - index of the field
     * @param int - index of the array
     * @param object - the value
     */
    protected function _set_arr_value($index, $index_arr, $value)
    {
        $this->values[$index][$index_arr] = $value;
    }

    /**
     * Remove the last array value
     * @param int - index of the field
     */
    protected function _remove_last_arr_value($index)
    {
    	array_pop($this->values[$index]);
    }

    /**
     * Set an value
     * @param int - index of the field
     * @param Mixed value
     */
    protected function _set_value($index, $value)
    {
        if (gettype($value) == 'object')
        {
            $this->values[$index] = $value;
        }
        else
        {
            $this->values[$index] = new $this->fields[$index]();
            $this->values[$index]->value = $value;
        }
    }

    /**
     * Get a value
     * @param id of the field
     */
    protected function _get_value($index)
    {
        if ($this->values[$index] == null)
            return null;
        return $this->values[$index]->value;
    }

    /**
     * Get array value
     * @param id of the field
     * @param value
     */
    protected function _get_arr_value($index, $value)
    {
        return $this->values[$index][$value];
    }

    /**
     * Get array size
     * @param id of the field
     */
    protected function _get_arr_size($index)
    {
        return count($this->values[$index]);
    }

    /**
     * Helper method for send string
     */
    protected function _save_string($ch, $string)
    {
        $this->_d_string .= $string;
        $content_length = strlen($this->_d_string);
        return strlen($string);
    }

    /**
     * Sends the message via post request ['message'] to the url
     * @param the url
     * @param the PBMessage class where the request should be encoded
     *
     * @return String - the return string from the request to the url
     */
    public function Send($url, &$class = null)
    {
        $ch = curl_init();
        $this->_d_string = '';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, array($this, '_save_string'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'message=' . urlencode($this->SerializeToString()));
        $result = curl_exec($ch);

        if ($class != null)
            $class->parseFromString($this->_d_string);
        return $this->_d_string;
    }
    
 	/**
     * Fix Memory Leaks with Objects in PHP 5
     * http://paul-m-jones.com/?p=262
     * 
     * thanks to cheton
     * http://code.google.com/p/pb4php/issues/detail?id=3&can=1
     */
    public function __destruct()
    {
        if (isset($this->reader))
        {
            unset($this->reader);
        }
        if (isset($this->value))
        {
            unset($this->value);
        }
        // base128
        if (isset($this->base128))
        {
           unset($this->base128);
        }
        // fields
        if (isset($this->fields))
        {
            foreach ($this->fields as $name => $value)
            {
                unset($this->$name);
            }
            unset($this->fields);
        }
        // values
        if (isset($this->values))
        {
            foreach ($this->values as $name => $value)
            {
                if (is_array($value))
                {
                    foreach ($value as $name2 => $value2)
                    {
                        if (is_object($value2) AND method_exists($value2, '__destruct'))
                        {
                            $value2->__destruct();
                        }
                        unset($value2);
                    }
                    if (isset($name2))
                    	unset($value->$name2);
                }
                else
                {
                    if (is_object($value) AND method_exists($value, '__destruct'))
                    {
                        $value->__destruct();
                    }
                    unset($value);
                }
                unset($this->values->$name);
            }
            unset($this->values);
        }
    }
    
}
?>
