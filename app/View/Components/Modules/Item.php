<?php

namespace App\View\Components\Modules;

use App\Abstracts\View\Component;

class Item extends Component
{
    public $module;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model
    ) {
        $this->module = $model;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.modules.item');
    }
}
