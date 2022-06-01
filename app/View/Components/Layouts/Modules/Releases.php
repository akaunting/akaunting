<?php

namespace App\View\Components\Layouts\Modules;

use App\Abstracts\View\Component;

class Releases extends Component
{
    public $releases;

    public function __construct(
        $releases = []
    ) {
        $this->releases = $releases;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.layouts.modules.releases');
    }
}
