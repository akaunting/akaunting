<?php

namespace App\Events\Menu;

use App\Abstracts\Event;

class SettingsCreating extends Event
{
    public $menu;

    /**
     * Create a new event instance.
     *
     * @param $menu
     */
    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}
