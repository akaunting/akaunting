<?php

namespace App\View\Components\Loading;

use App\Abstracts\View\Component;

class Content extends Component
{
    public $hidden;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        bool $hidden = false,
       
    ) {
        $this->hidden = $hidden;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.loading.content');
    }
}