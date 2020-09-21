<?php

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class BulkActionsAdding
{
    use SerializesModels;

    public $bulk_action;

    /**
     * Create a new event instance.
     *
     * @param $bulk_action
     */
    public function __construct($bulk_action)
    {
        $this->bulk_action = $bulk_action;
    }
}
