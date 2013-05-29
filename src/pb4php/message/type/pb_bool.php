<?php
/**
 * @author Nikolai Kordulla
 */
class PBBool extends PBInt
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
		$value = $reader->next();
		return ($value != 0) ? 1 : 0;
	}
	
	public static function StaticSerializeToString($rec=-1, $base128, $value)
	{
	    return PBInt::StaticSerializeToString($rec, $base128, $value);
	}
}
?>
