<?php
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
require_once(dirname(__FILE__). '/' . 'type/pb_double.php');
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
    public function __construct(&$reader=null, $base128=null)
    {
        $this->reader = &$reader;
        $this->value = $this;
        if ($base128 === null)            
            $this->base128 = new base128varint();
        else
            $this->base128 = $base128;
    }

    /**
     * Get the wired_type and field_type
     * @param $number as decimal
     * @return array wired_type, field_type
     */
    public function get_types($number)
    {
        $types = array();
        $types['wired'] = $number & 7;
        $types['field'] = ($number ^ 7) >> 3;
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
            if (is_array($this->values[$index]))
            {
                if (count($this->values[$index]) > 0)
                {
                    $isInlineType = strncmp($this->fields[$index], "\\I_", 3) === 0;
                    if ($isInlineType) $I_type = "\\" . substr($this->fields[$index], 3);
                
                    // make serialization for every array
                    foreach ($this->values[$index] as $array)
                    {
                        if ($isInlineType)
                            $stringinner .= $I_type::StaticSerializeToString($index, $this->base128, $array);
                        else
                            $stringinner .= $array->SerializeToString($index);
                    }
                }
            }
            else if (isset($this->values[$index]))
            {
                if (strncmp($this->fields[$index], "\\I_", 3) === 0)
                {
                    $I_type = "\\" . substr($this->fields[$index], 3);
                    $stringinner .= $I_type::StaticSerializeToString($index, $this->base128, $this->values[$index]);
                }
                else
                {
                    // wired and type
                    $stringinner .= $this->values[$index]->SerializeToString($index);
                }
            }
        }

        $this->_serialize_chunk($stringinner);

        if ($this->wired_type == PBMessage::WIRED_LENGTH_DELIMITED && $rec > -1)
        {
            $stringinner = $this->base128->set_value(strlen($stringinner)) . $stringinner;
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
                    $consume = new PBString($this->reader, $this->base128);
                }
                else if ($messtypes['wired'] == PBMessage::WIRED_VARINT)
                {
                    $consume = new PBInt($this->reader, $this->base128);
                }
                else
                {
                    throw new Exception('I dont understand this wired code: ' . $messtypes['wired']);
                }

                // perhaps send a warning out
                // @TODO SEND CHUNK WARNING
                $_oldpointer = $this->reader->get_pointer();
                $consume->ParseFromArray();
                // now add array from _oldpointer to pointer to the chunk array
                $this->chunk .= $this->reader->get_message_from($_oldpointer);
                continue;
            }

            $outputVar = &$this->values[$messtypes['field']];
            // is it an array?
            if (is_array($outputVar))
            {
                $outputVar[] = null;
                $index = count($outputVar) - 1;
                $outputVar = &$outputVar[$index];
            }

            $type = $this->fields[$messtypes['field']];
            if (strncmp($type, "\\I_", 3) === 0)
            {
                $I_type = "\\" . substr($type, 3);
                if ($messtypes['wired'] != $I_type::$static_wired_type)
                {
                    throw new Exception('Expected type:' . $messtypes['wired'] . ' but had ' . $I_type::$static_wired_type);
                }
                $outputVar = $I_type::StaticParseFromArray($this->reader);
            }
            else
            {
                $outputVar = new $type($this->reader, $this->base128);
                if ($messtypes['wired'] != $outputVar->wired_type)
                {
                    throw new Exception('Expected type:' . $messtypes['wired'] . ' but had ' . $this->fields[$messtypes['field']]->wired_type);
                }
                $outputVar->ParseFromArray();
            }
        }
    }

    /**
     * Add an array value
     * @param int - index of the field
     */
    protected function _add_arr_value($index)
    {
        return $this->values[$index][] = new $this->fields[$index](null, $this->base128);
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
            if (strncmp($this->fields[$index], "\\I_", 3) === 0)
            {
                $this->values[$index] = $value;
            }
            else
            {
                $nullReader = null;
                $this->values[$index] = new $this->fields[$index]($nullReader, $this->base128);
                $this->values[$index]->value = $value;
            }
        }
    }

    /**
     * Get a value
     * @param id of the field
     */
    protected function _get_value($index)
    {
        if ($this->values[$index] === null)
            return null;
        if (strncmp($this->fields[$index], "\\I_", 3) === 0)
            return $this->values[$index];
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
}
?>
