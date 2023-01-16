<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class Color extends Form
{
    public $type = 'color';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.group.color');
    }
}
