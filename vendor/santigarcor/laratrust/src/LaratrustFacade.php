<?php

namespace Laratrust;

use Illuminate\Support\Facades\Facade;

class LaratrustFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laratrust';
    }
}
