<?php

namespace App\View\Components\Transactions\Template;

use App\Abstracts\View\Components\TransactionTemplate as Component;

class Ddefault extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.transactions.template.default');
    }
}
