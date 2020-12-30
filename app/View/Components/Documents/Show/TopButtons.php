<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\DocumentShow as Component;

class TopButtons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.show.top-buttons');
    }
}
