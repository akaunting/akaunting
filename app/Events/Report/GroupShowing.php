<?php

namespace App\Events\Report;

use App\Abstracts\Event;

class GroupShowing extends Event
{
    public $class;

    /**
     * Create a new event instance.
     *
     * @param $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }
}
