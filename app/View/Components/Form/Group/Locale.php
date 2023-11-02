<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class Locale extends Form
{
    public $type = 'locale';

    public $name = 'locale';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if ($this->selected === null && empty($this->getParentData('model'))) {
            $this->selected = setting('default.locale', config('app.locale', 'en-GB'));
        }

        return view('components.form.group.locale');
    }
}
