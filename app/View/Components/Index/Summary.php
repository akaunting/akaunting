<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;

class Summary extends Component
{
    public $items;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $items = []
    ) {
        $this->items = $items;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.summary');
    }
}
