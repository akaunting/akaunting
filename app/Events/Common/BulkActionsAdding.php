<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class BulkActionsAdding extends Event
{
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
