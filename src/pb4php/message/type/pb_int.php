<?php
/**
 * @author Nikolai Kordulla
 */
class PBInt extends PBScalar
{
	static $static_wired_type = PBMessage::WIRED_VARINT;
	var $wired_type = PBMessage::WIRED_VARINT;

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
	    return $reader->next();
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
			$string = $base128->set_value($rec << 3 | self::$static_wired_type);
		}

		return $string . $base128->set_value($value);
	}
}
?>
