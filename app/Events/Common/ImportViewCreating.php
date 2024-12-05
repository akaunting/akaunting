<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class ImportViewCreating extends Event
{
    public $view;

    /**
     * Create a new event instance.
     *
     * @param $view
     * 
     */
    public function __construct($view)
    {
        $this->view = $view;
    }
}
