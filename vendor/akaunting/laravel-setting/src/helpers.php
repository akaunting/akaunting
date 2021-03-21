<?php

if (!function_exists('array_keys_exists')) {
    /**
     * Easily check if multiple array keys exist.
     *
     * @param array $keys
     * @param array $arr
     *
     * @return boolean
     */
    function array_keys_exists(array $keys, array $arr)
    {
        return !array_diff_key(array_flip($keys), $arr);
    }
}

if (!function_exists('setting')) {
    /**
     * Get / set the specified setting value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string $key
     * @param mixed $default
     *
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        $setting = app('setting');

        if (is_null($key)) {
            return $setting;
        }

        if (is_array($key)) {
            $setting->set($key);

            return $setting;
        }

        return $setting->get($key, $default);
    }
}
