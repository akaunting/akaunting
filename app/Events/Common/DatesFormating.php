<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class DatesFormating extends Event
{
    public $columns;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $request
     */
    public function __construct($columns, $request)
    {
        $this->columns = $columns;
        $this->request = $request;
    }
}
