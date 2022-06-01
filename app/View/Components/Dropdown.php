<?php

namespace App\View\Components;

use App\Abstracts\View\Component;

class Dropdown extends Component
{
    public $id;

    public $override;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id = '', $override = ''
    ) {
        $this->id = $id;
        $this->override = explode(',', $override);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.dropdown.index');
    }
}
