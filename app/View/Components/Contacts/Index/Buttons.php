<?php

namespace App\View\Components\Contacts\Index;

use App\Abstracts\View\Components\Contacts\Index as Component;

class Buttons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.contacts.index.buttons');
    }
}
