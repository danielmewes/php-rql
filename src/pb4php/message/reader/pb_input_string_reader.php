<?php
/**
 * Reads string input
 */
class PBInputStringReader extends PBInputReader
{
	var $length = 0;

	public function __construct($string)
	{
		parent::__construct();
		$this->string = $string;
		$this->length = strlen($string);
	}

	/**
	 * get the next
	 * @param boolean $is_string - if set to true only one byte is read
	 */
	public function next()
	{
	    $startPointer = $this->pointer;
		while (true)
		{
			if ($this->pointer >= $this->length)
				return false;

			$byte = $this->string[$this->pointer];
			++$this->pointer;
			
			if (ord($byte) < 128)
			    break; // MSB not set, now go and calculate the value
		}	
		$package = substr($this->string, $startPointer, $this->pointer - $startPointer);
		// Calculate the value
		return $this->base128->get_value($package);
	}
}
?>
