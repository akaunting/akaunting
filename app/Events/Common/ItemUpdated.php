<?php

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class ItemUpdated
{
    use SerializesModels;

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
