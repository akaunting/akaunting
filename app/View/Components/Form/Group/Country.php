<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class Country extends Form
{
    public $type = 'country';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'country';
        }

        if ($this->selected === null && empty($this->getParentData('model'))) {
            $this->selected = setting('company.country');
        }

        return view('components.form.group.country');
    }
}
