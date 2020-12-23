<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\DocumentShow as Component;

class Histories extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.show.histories');
    }
}
