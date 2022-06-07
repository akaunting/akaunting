<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class Country extends Form
{
    public $type = 'country';

    /** @var string */
    public $formGroupClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $formGroupClass = 'sm:col-span-3') {
        $this->formGroupClass = $formGroupClass;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.group.country');
    }
}
