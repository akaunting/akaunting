<?php

namespace App\View\Components\Layouts;

use App\Abstracts\View\Component;

class Wizard extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.layouts.wizard');
    }
}
