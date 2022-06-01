<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class Attachment extends Form
{
    public $name = 'attachment';

    public $type = 'file';

    public $formGroupClass = 'sm:col-span-3';

    public $custom_attributes = [
        'dropzone-class' => 'form-file dropzone-column w-1/2 h-32.5',
    ];

    public $file_types;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->setFileTypes();

        $this->custom_attributes = $this->setCustomAttributes();

        return view('components.form.group.attachment');
    }

    protected function setFileTypes()
    {
        $this->file_types = [];

        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $this->file_types = implode(',', $file_types);
    }

    protected function setCustomAttributes()
    {
        $attributes = [];

        if (! empty($this->required)) {
            $attributes['required'] = $this->required;
        }

        if (! empty($this->disabled)) {
            $attributes['disabled'] = $this->disabled;
        }

        if (! empty($this->readonly)) {
            $attributes['readonly'] = $this->readonly;
        }

        if (! empty($this->options)) {
            $attributes['options'] = $this->options;
        }

        if (! empty($this->multiple)) {
            $attributes['multiple'] = $this->multiple;
        }

        foreach ($this->custom_attributes as $key => $value) {
            $attributes[$key] = $value;
        }

        return $attributes;
    }
}
