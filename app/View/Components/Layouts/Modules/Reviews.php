<?php

namespace App\View\Components\Layouts\Modules;

use App\Abstracts\View\Component;

class Reviews extends Component
{
    public $reviews;

    public function __construct(
        $reviews = []
    ) {
        $this->reviews = $reviews;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.layouts.modules.reviews');
    }
}
