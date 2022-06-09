<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class Radio extends Form
{
    public $type = 'radio';

    /** @var string */
    public $formGroupClass = 'sm:col-span-6';

    /** @var string */
    public $inputGroupClass = 'grid grid-cols-2 gap-3 sm:grid-cols-4';

    public $except = [
        
    ];

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.group.radio');
    }
}
