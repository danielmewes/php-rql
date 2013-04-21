<?php
/**
 * @author Nikolai Kordulla
 */
class PBSignedInt extends PBScalar
{
	var $wired_type = PBMessage::WIRED_VARINT;

	/**
	 * Parses the message for this type
	 *
	 * @param array
	 */
	public function ParseFromArray()
	{
		parent::ParseFromArray();

		$saved = $this->value;
		$this->value = round($this->value / 2);
		if ($saved % 2 == 1)
		{
			$this->value = -($this->value);
		}
	}

	/**
	 * Serializes type
	 */
	public function SerializeToString($rec=-1)
	{
		// now convert signed int to int
		$save = $this->value;
		if ($this->value < 0)
		{
			$this->value = abs($this->value)*2-1;
		}
		else 
		{
			$this->value = $this->value*2;
		}
		$string = parent::SerializeToString($rec);
		// restore value
		$this->value = $save;

		return $string;
	}
}
?>
