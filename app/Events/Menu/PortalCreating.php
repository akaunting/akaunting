<?php

namespace App\Events\Menu;

use App\Abstracts\Event;

class PortalCreating extends Event
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
