<?php
/**
 * @author Dmitry Vorobyev (http://dmitry.vorobyev.name)
 * Implementation from https://code.google.com/p/pb4php/issues/detail?id=16
 */
class PBDouble extends PBScalar
{
	var $wired_type = PBMessage::WIRED_64BIT;

	public function ParseFromArray()
    {
        $this->value = '';

        // just extract the string
        $pointer = $this->reader->get_pointer();
        $this->reader->add_pointer(8);
        $this->value = unpack('d', $this->reader->get_message_from($pointer));
        $this->value = $this->value[1];
    }
    
    /**
     * Serializes type
     */
    public function SerializeToString($rec=-1)
    {
        $string = '';
        if ($rec > -1)
        {
            $string = $this->base128->set_value($rec << 3 |
$this->wired_type);
        }
        
        $string .= pack("d", (double)$this->value); 
        
        return $string;
    }
}
?>
