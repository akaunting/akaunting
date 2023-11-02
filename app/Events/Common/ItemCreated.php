<?php

namespace App\Events\Common;

use App\Abstracts\Event;
use App\Models\Common\Item;

class ItemCreated extends Event
{
    public $item;

    /**
     * Create a new event instance.
     *
     * @param $item
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}
