<?php

namespace BitSensor\Hook;

class Util
{
    /**
     * Prepend a reference to an element to the beginning of an array. Renumbers numeric keys, so $value is always inserted to $array[0].
     *
     * @param $array array
     * @param $value mixed
     * @return int
     */
    public static function array_unshift_ref(&$array, &$value)
    {
        $return = array_unshift($array, '');
        $array[0] =& $value;
        return $return;
    }

    /**
     * Returns first element found in the array.
     *
     * @param array $xs
     * @param callable $f
     * @return array element if found or null
     */
    public static function array_find($xs, $f)
    {
        foreach ($xs as $x) {
            if (call_user_func($f, $x) === true)
                return $x;
        }
        return null;
    }
}

