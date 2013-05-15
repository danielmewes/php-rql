<?php

/**
 * Base 128 varints - decodes and encodes base128 varints to/from decimal
 * @author Nikolai Kordulla
 */
class base128varint
{
    // modus for output
    var $modus = 1;

    /**
     * @param int $modus - 1=Byte 2=String
     */
    public function __construct($modus)
    {
        $this->modus = $modus;
    }


    /**
     * @param $number - number as decimal
     * Returns the base128 value of an dec value
     */
    public function set_value($number)
    {
        if ($number < 128)
        {
            if ($this->modus == 1)
            {
                return pack("C", $number);
            }
            $hexstring = dechex($number);
            if (strlen($hexstring) % 2 == 1)
                $hexstring = '0' . $hexstring;
            return $hexstring;
        }

        // split it and insert the mb byte
        $string = decbin($number);
        $string_length = strlen($string);
        $string_array = array();
        $pre = '1';
        while ($string_length > 0)
        {
            if ($string_length < 8)
            {
                $string = substr('00000000', 0, 7 - $string_length % 7) . substr($string, 0, $string_length);
                $string_length = strlen($string);
                $pre = '0';
            }
            $string_array[] = $pre . substr($string, $string_length - 7, 7);
            $string_length -= 7;
            $pre = '1';
            if ($string_length == 7 && substr($string, 0, $string_length) == '0000000')
                break;
        }

        $hexstring = '';
        foreach ($string_array as $string)
        {
            $hexstring .= sprintf('%02X', bindec($string));
        }

        // now format to hexstring in the right format
        if ($this->modus == 1)
        {
            return $this->hex_to_str($hexstring);
        }

        return $hexstring;
    }


    /**
     * Returns the dec value of an base128
     * @param string bstring
     */
    public function get_value(&$string)
    {
        // now just drop the msb and reorder it + parse it in own string
        $valuestring = '';
        $string_length = strlen($string);

        $i = 1;

        while ($string_length > $i)
        {
            // unset msb string and reorder it
            $valuestring = substr($string, $i, 7) . $valuestring;
            $i += 8;
        }

        // now interprete it
        return bindec($valuestring);
    }

    /**
     * Converts hex 2 ascii
     * @param String $hex - the hex string
     */
    public function hex_to_str(&$hex)
    {
        $str = '';
        $length = strlen($hex);

        for($i = 0; $i < $length; $i += 2)
        {
            $str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $str;
    }
}

?>
