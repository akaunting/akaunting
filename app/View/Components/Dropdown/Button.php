<?php

namespace App\View\Components\Dropdown;

use App\Abstracts\View\Component;

class Button extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.dropdown.button');
    }
}
