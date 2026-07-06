<?php

namespace App\Events\Import;

use App\Abstracts\Event;

class RowPreparing extends Event
{
    public $class;

    public $row;

    /**
     * Create a new event instance.
     *
     * @param mixed $class
     * @param array $row
     */
    public function __construct($class, array $row)
    {
        $this->class = $class;
        $this->row = $row;
    }
}
