<?php

namespace App\View\Components\Form\Input;

use App\Abstracts\View\Components\Form;

class Input extends Form
{
    public $type = 'input';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.input.input');
    }
}
