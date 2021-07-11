<?php

namespace App\View\Components\Transfers\Template;

use App\Abstracts\View\Components\TransferTemplate as Component;

class Ddefault extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.transfers.template.default');
    }
}
