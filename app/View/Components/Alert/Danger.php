<?php

namespace App\View\Components\Alert;

use App\View\Components\Alert as Component;

class Danger extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.alert.danger');
    }
}
