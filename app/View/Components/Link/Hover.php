<?php

namespace App\View\Components\Link;

use App\Abstracts\View\Component;

class Hover extends Component
{
    public $color;

    public $groupHover;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $color = 'to-black',
        bool $groupHover = false,
       
    ) {
        $this->color = $color;
        $this->groupHover = $groupHover;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.link.hover');
    }
}
