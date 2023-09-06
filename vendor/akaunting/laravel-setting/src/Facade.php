<?php

namespace Akaunting\Setting;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    /**
     * Get the registered name of the component.
     */
    public static function getFacadeAccessor()
    {
        return 'setting';
    }
}
