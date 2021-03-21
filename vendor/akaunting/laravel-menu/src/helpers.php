<?php

if (!function_exists('menu')) {
    /**
     * Get the Menu instance or render
     *
     * @param string $name
     *
     * @return mixed
     */
    function menu($name = null)
    {
        $menu = app('menu');
        
        if (is_null($name)) {
            return $menu;
        }
        
        return $menu->get($name);
    }
}
