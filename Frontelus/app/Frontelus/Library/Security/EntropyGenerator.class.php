<?php

namespace Frontelus\Library\Security;

class EntropyGenerator
{

    public function createNip($cut = false)
    {
        # generating numbers
        $time = (date('U'));
        $timesh = str_shuffle($time);

        # counting length
        $count = strlen($time);

        # converting variables to array 
        $arraytime = str_split($time);
        $arraytimesh = str_split($timesh);

        $buffer = "";
        $buffer1 = "";

        # adding contained digits into arrays
        foreach ($arraytime as $value)
        {
            $buffer .= $buffer1;
            foreach ($arraytimesh as $value2)
            {
                $buffer1 = $value + $value2;
            }
        }

        # numbers ready for use
        $buffer *= $count;
        $ran = str_shuffle(rand(0, 514370487));
        list($usec, $sec) = explode(' ', microtime());

        # operations over the previous numbers
        $operTemp = abs($ran - $buffer);
        $oper = str_shuffle((sqrt($operTemp) / $usec) + $sec) + $count;
        $result = str_shuffle(base_convert($oper, 10, 16));
        $count2 = strlen($result);

        if ($cut != false)
        {
            if ($cut < $count2)
            {
                $result = substr($result, 0, $cut);
            }
        }

        return $result;
    }

}
