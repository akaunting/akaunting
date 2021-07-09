<?php

namespace App\View\Components\Transfers\Show;

use App\Abstracts\View\Components\TransferShow as Component;

class Histories extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.transfers.show.histories');
    }
}
