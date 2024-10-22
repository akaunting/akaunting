<?php

namespace App\Events\Report;

use App\Abstracts\Event;

class SkipRowsShowing extends Event
{
    public $classes;

    /**
     * Create a new event instance.
     *
     * @param $classes
     */
    public function __construct($classes)
    {
        $this->classes = $classes;
    }
}
