<?php

namespace App\View\Components\Dropdown;

use App\Abstracts\View\Component;

class Link extends Component
{
    public $href;
    public $target;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $href = '', string $target = '_self'
    ) {
        $this->href     = $href;
        $this->target   = $target;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.dropdown.link');
    }
}
