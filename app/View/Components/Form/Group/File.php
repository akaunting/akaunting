<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class File extends Form
{
    public $type = 'file';

    public $formGroupClass = 'sm:col-span-3';

    public $custom_attributes = [
        'dropzone-class' => 'form-file dropzone-column sm:w-1/2 h-32.5',
    ];

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

        $this->options['maxFilesize'] = config('filesystems.max_size');

        return view('components.form.group.file');
    }
}
