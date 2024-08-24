<?php

namespace App\View\Components\Transactions\Index;

use App\Abstracts\View\Components\Transactions\Index as Component;

class MoreButtons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.transactions.index.more-buttons');
    }
}
