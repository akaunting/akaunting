<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class Textarea extends Form
{
    public $type = 'textarea';

    public $formGroupClass = 'sm:col-span-6';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.group.textarea');
    }
}
