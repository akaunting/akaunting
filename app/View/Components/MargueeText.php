<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class MargueeText extends Component
{
    public $width;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
         string $width = 'auto'
    ) {
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.marguee-text');
    }
}
