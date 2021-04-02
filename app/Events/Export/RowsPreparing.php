<?php

namespace App\Events\Export;

use App\Abstracts\Event;

class RowsPreparing extends Event
{
    public $class;

    public $rows;

    /**
     * Create a new event instance.
     *
     * @param $class
     * @param $rows
     */
    public function __construct($class, $rows)
    {
        $this->class = $class;
        $this->rows = $rows;
    }
}
