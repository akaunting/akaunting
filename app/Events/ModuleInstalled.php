<?php

namespace App\Events;

class ModuleInstalled
{
    public $alias;
    public $console;

    /**
     * Create a new event instance.
     *
     * @param  $alias
     */
    public function __construct($alias,$console = null)
    {
        $this->alias = $alias;
        $this->console = $console;
    }
}