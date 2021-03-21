<?php

if (!function_exists('module')) {
    /**
     * Get the Module instance
     *
     * @param string $alias
     *
     * @return mixed
     */
    function module($alias = null)
    {
        $module = app('module');

        if (is_null($alias)) {
            return $module;
        }

        return $module->get($alias);
    }
}

if (!function_exists('module_attribute')) {
    /**
     * Get an attribute from module.json file
     *
     * @param string $alias
     * @param string $attribute
     *
     * @return mixed
     */
    function module_attribute($alias, $attribute)
    {
        return app('module')->get($alias)->get($attribute);
    }
}

if (!function_exists('module_version')) {
    /**
     * Get the module version
     *
     * @param string $alias
     *
     * @return mixed
     */
    function module_version($alias)
    {
        return app('module')->get($alias)->get('version');
    }
}

if (!function_exists('module_path')) {
    /**
     * Get the module path
     *
     * @param string $alias
     *
     * @return mixed
     */
    function module_path($alias, $path = '')
    {
        return app('module')->get($alias)->getPath() . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     *
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param string $path
     *
     * @return string
     */
    function public_path($path = '')
    {
        return app()->make('path.public') . ($path ? '/' . $path : $path);
    }
}
