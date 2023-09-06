<?php

namespace Akaunting\Setting\Support;

class Arr
{
    /**
     * This class is a static class and should not be instantiated.
     */
    private function __construct()
    {
        //
    }

    /**
     * Get an element from an array.
     *
     * @param array $data
     * @param string $key     Specify a nested element by separating keys with full stops.
     * @param mixed $default If the element is not found, return this.
     *
     * @return mixed
     */
    public static function get(array $data, $key, $default = null)
    {
        if ($key === null) {
            return $data;
        }

        if (is_array($key)) {
            return static::getArray($data, $key, $default);
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($data)) {
                return $default;
            }

            if (!array_key_exists($segment, $data)) {
                return $default;
            }

            $data = $data[$segment];
        }

        return $data;
    }

    protected static function getArray(array $input, $keys, $default = null)
    {
        $output = [];

        foreach ($keys as $key) {
            static::set($output, $key, static::get($input, $key, $default));
        }

        return $output;
    }

    /**
     * Determine if an array has a given key.
     *
     * @param array $data
     * @param string $key
     *
     * @return bool
     */
    public static function has(array $data, $key)
    {
        foreach (explode('.', $key) as $segment) {
            if (!is_array($data)) {
                return false;
            }

            if (!array_key_exists($segment, $data)) {
                return false;
            }

            $data = $data[$segment];
        }

        return true;
    }

    /**
     * Set an element of an array.
     *
     * @param array $data
     * @param string $key   Specify a nested element by separating keys with full stops.
     * @param mixed $value
     */
    public static function set(array &$data, $key, $value)
    {
        $segments = explode('.', $key);

        $key = array_pop($segments);

        // iterate through all of $segments except the last one
        foreach ($segments as $segment) {
            if (!array_key_exists($segment, $data)) {
                $data[$segment] = array();
            } elseif (!is_array($data[$segment])) {
                throw new \UnexpectedValueException('Non-array segment encountered');
            }

            $data = &$data[$segment];
        }

        $data[$key] = $value;
    }

    /**
     * Unset an element from an array.
     *
     * @param array &$data
     * @param string $key   Specify a nested element by separating keys with full stops.
     */
    public static function forget(array &$data, $key)
    {
        $segments = explode('.', $key);

        $key = array_pop($segments);

        // iterate through all of $segments except the last one
        foreach ($segments as $segment) {
            if (!array_key_exists($segment, $data)) {
                return;
            } elseif (!is_array($data[$segment])) {
                throw new \UnexpectedValueException('Non-array segment encountered');
            }

            $data = &$data[$segment];
        }

        unset($data[$key]);
    }

    /**
     * Merge two multidimensional arrays recursive
     *
     * @param array $array_1
     * @param array $array_2
     *
     * @return array
     */
    public static function merge(array $array_1, array $array_2)
    {
        $merged = $array_1;

        foreach ($array_2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::merge($merged[$key], $value);
            } elseif (is_numeric($key)) {
                if (!in_array($value, $merged)) {
                    $merged[] = $value;
                }
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
