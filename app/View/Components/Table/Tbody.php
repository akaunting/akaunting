<?php

namespace App\View\Components\Table;

use App\Abstracts\View\Component;

class Tbody extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.table.tbody');
    }
}
