<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class Checkbox extends Form
{
    public $type = 'checkbox';

    /** @var string */
    public $formGroupClass = 'sm:col-span-6';

    public $except = [
        
    ];

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.group.checkbox');
    }
}
