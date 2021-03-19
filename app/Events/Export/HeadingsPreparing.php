<?php

namespace App\Events\Export;

use App\Abstracts\Event;

class HeadingsPreparing extends Event
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
