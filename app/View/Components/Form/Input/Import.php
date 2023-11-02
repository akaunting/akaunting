<?php

namespace App\View\Components\Form\Input;

use App\Abstracts\View\Components\Form;

class Import extends Form
{
    public $type = 'file';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (! empty($this->options)) {
            $options = [];

            foreach ($this->options as $option) {
                $options[$option->id] = $option->name;
            }

            $this->options = $options;
        }

        return view('components.form.input.import');
    }
}
