<?php

if (!function_exists('version')) {
    /**
     * Get the Version instance or a specific method.
     *
     * @param string $method
     *
     * @return mixed
     */
    function version($method = null)
    {
        $version = app('version');

        if (is_null($method)) {
            return $version;
        }

        return $version->$method();
    }
}
