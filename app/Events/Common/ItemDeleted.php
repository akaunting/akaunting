<?php

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class ItemDeleted
{
    use SerializesModels;

    public $item_id;

    /**
     * Create a new event instance.
     *
     * @param $item_id
     */
    public function __construct($item_id)
    {
        $this->item_id = $item_id;
    }
}
