<?php

namespace App\View\Components\Transactions\Show;

use App\Abstracts\View\Components\Transactions\Show as Component;

class Content extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.transactions.show.content');
    }
}
