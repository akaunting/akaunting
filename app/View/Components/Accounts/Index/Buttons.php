<?php

namespace App\View\Components\Accounts\Index;

use App\Abstracts\View\Components\Accounts\Index as Component;

class Buttons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.accounts.index.buttons');
    }
}
