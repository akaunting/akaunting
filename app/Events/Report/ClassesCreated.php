<?php

namespace App\Events\Report;

use App\Abstracts\Event;

class ClassesCreated extends Event
{
    public $list;

    /**
     * Create a new event instance.
     *
     * @param $list
     */
    public function __construct($list)
    {
        $this->list = $list;
    }
}
