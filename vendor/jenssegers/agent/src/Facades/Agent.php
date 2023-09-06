<?php

namespace Jenssegers\Agent\Facades;

use Illuminate\Support\Facades\Facade;

class Agent extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'agent';
    }
}
