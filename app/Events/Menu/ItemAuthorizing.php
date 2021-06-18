<?php

namespace App\Events\Menu;

use App\Abstracts\Event;

class ItemAuthorizing extends Event
{
    public $item;

    /**
     * Create a new event instance.
     *
     * @param $item
     */
    public function __construct($item)
    {
        $this->item = $item;
    }
}
