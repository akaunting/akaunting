<?php

namespace App\View\Components\Layouts;

use App\Abstracts\View\Component;

class Admin extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.layouts.admin');
    }
}
