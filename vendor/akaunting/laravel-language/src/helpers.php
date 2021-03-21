<?php

if (!function_exists('language')) {
    /**
     * Get the language instance.
     *
     * @return \Akaunting\Language\Language
     */
    function language()
    {
        return app('language');
    }
}
