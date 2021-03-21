<?php

if (! function_exists('link_to')) {
    /**
     * Generate a HTML link.
     *
     * @param string $url
     * @param string $title
     * @param array  $attributes
     * @param bool   $secure
     * @param bool   $escape
     *
     * @return \Illuminate\Support\HtmlString
     */
    function link_to($url, $title = null, $attributes = [], $secure = null, $escape = true)
    {
        return app('html')->link($url, $title, $attributes, $secure, $escape);
    }
}

if (! function_exists('link_to_asset')) {
    /**
     * Generate a HTML link to an asset.
     *
     * @param string $url
     * @param string $title
     * @param array  $attributes
     * @param bool   $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    function link_to_asset($url, $title = null, $attributes = [], $secure = null)
    {
        return app('html')->linkAsset($url, $title, $attributes, $secure);
    }
}

if (! function_exists('link_to_route')) {
    /**
     * Generate a HTML link to a named route.
     *
     * @param string $name
     * @param string $title
     * @param array  $parameters
     * @param array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    function link_to_route($name, $title = null, $parameters = [], $attributes = [])
    {
        return app('html')->linkRoute($name, $title, $parameters, $attributes);
    }
}

if (! function_exists('link_to_action')) {
    /**
     * Generate a HTML link to a controller action.
     *
     * @param string $action
     * @param string $title
     * @param array  $parameters
     * @param array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    function link_to_action($action, $title = null, $parameters = [], $attributes = [])
    {
        return app('html')->linkAction($action, $title, $parameters, $attributes);
    }
}
