<?php

namespace App\View\Components\Transfers\Show;

use App\Abstracts\View\Components\Transfers\Show as Component;

class Create extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.transfers.show.create');
    }
}
