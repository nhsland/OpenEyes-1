<?php

class ArrayHelper
{
    /**
     * Get only the values from a multi-dimensional array
     *
     * @param $arr array to get values from
     *
     * @return array
     */
    public static function array_values_multi($arr)
    {
        $result = array();
        self::get_values($result, $arr);
        return $result;
    }

    /**
     * Recursively adds every value in the $arr array to $return
     *
     * @param $return array passed by reference to put all values found
     * @param $arr array to get the values from
     */
    private static function get_values(&$return, $arr)
    {
        foreach ($arr as $index => $value) {
            if (is_array($value)) {
                self::get_values($return, $value);
            } else {
                $return[] = $value;
            }
        }
    }

    /**
     *
     */
    public static function array_dump_html($arr)
    {
        $return_str = '';
        foreach ($arr as $element) {
            if (!is_array($element)) {
                $return_str .= '<li>'.$element.'</li>';
            } else {
                $return_str .= '<div style="padding-left: 50px">'.self::array_dump_html($element).'</div>';
            }
        }
        return $return_str;
    }
}