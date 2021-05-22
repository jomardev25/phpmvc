<?php

namespace App\Core\Utils;

use ArrayAccess;

class Arr
{
    public static function first($array)
    {
        return array_shift($array);
    }

    public static function last($array)
    {
        return end($array);
    }

    public static function get($array, $key, $default = null)
    {
        if (! static::accessible($array)) {
            return $default;
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (strpos($key, ".") === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode(".", $key) as $segment) {
            print_r($segment);
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}