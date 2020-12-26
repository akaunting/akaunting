<?php

namespace App\View\Components\Documents\Index;

use App\Abstracts\View\Components\DocumentIndex as Component;

class CardBody extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.index.card-body');
    }
}
