<?php

namespace App\Events;

class ModuleInstalled
{
    public $alias;

    /**
     * Create a new event instance.
     *
     * @param  $alias
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
    }
}