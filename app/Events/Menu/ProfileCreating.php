<?php

namespace App\Events\Menu;

use App\Abstracts\Event;

class ProfileCreating extends Event
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
