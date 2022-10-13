<?php

namespace App\View\Components\Show;

use App\Abstracts\View\Component;

class Right extends Component
{
    
    public $disableLoading = false;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.show.content.right');
    }
}
