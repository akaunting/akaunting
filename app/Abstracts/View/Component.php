<?php

namespace App\Abstracts\View;

use Illuminate\View\Component as BaseComponent;

abstract class Component extends BaseComponent
{
    public function getParentData($key, $default = null)
    {
        return app('view')->getConsumableComponentData($key, $default);
    }
}
