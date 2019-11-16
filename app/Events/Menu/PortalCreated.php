<?php

namespace App\Events\Menu;

use Illuminate\Queue\SerializesModels;

class PortalCreated
{
    use SerializesModels;

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
