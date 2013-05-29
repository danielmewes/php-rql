<?php

/**
 * Base 128 varints - decodes and encodes base128 varints to/from decimal
 * @author Nikolai Kordulla
 */
class base128varint
{
    /**
     * @param $number - number as decimal
     * Returns the base128 value of an dec value
     */
    public function set_value($number)
    {
        if ($number < 128 && $number >= 0)
        {
            return pack("C", $number);
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
        return $this->hex_to_str($hexstring);
    }


    /**
     * Returns the dec value of an base128
     * @param string bstring
     */
    public function get_value($string)
    {
        $value = 0;
        $string_length = strlen($string);

        $digit = 0;
        for ($i = 0; $i < $string_length; ++$i)
        {
            // drop msb and sum up
            $value += (ord($string[$i]) & 127) << $digit;
            $digit += 7;
        }

        return $value;
    }

    /**
     * Converts hex 2 ascii
     * @param String $hex - the hex string
     */
    public function hex_to_str($hex)
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
