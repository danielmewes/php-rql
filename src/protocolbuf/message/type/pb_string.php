<?php
/**
 * @author Nikolai Kordulla
 */
class PBString extends PBScalar
{
    static $static_wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
	var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;

	/**
	 * Parses the message for this type
	 *
	 * @param array
	 */
	public function ParseFromArray()
	{
	    $this->value = self::StaticParseFromArray($this->reader);
	}
	
	public static function StaticParseFromArray(&$reader)
	{
		// first byte is length
		$length = $reader->next();
		
		if ($length == 0)
		    return "";

		// just extract the string
		$pointer = $reader->get_pointer();
		$reader->add_pointer($length);
		return $reader->get_message_from($pointer);
	}

	/**
	 * Serializes type
	 */
	public function SerializeToString($rec=-1)
	{
		return self::StaticSerializeToString($rec, $this->base128, $this->value);
	}
	
	public static function StaticSerializeToString($rec=-1, $base128, $value)
	{
		$string = '';

		if ($rec > -1)
		{
			$string .= $base128->set_value($rec << 3 | self::$static_wired_type);
		}

		$string .= $base128->set_value(strlen($value));
		$string .= $value;

		return $string;
	}
}
?>
