<?php

namespace App\Events\Common;

use App\Abstracts\Event;
use App\Models\Common\Item;

class ItemUpdated extends Event
{
    public $item;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $item
     * @param $request
     */
    public function __construct(Item $item, $request)
    {
        $this->item = $item;
        $this->request = $request;
    }
}
