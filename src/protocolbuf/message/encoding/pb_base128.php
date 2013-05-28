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
        $string = '';
        while ($number > 0) {
            $msb = ($number > 127) ? 128 : 0;
            $string .= chr($number & 127 | $msb);
            $number = $number >> 7;
        }
        $newMethod = $string;
        return $string;
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
}

?>
