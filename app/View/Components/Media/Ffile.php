<?php

namespace App\View\Components\Media;

use App\Abstracts\View\Component;

class Ffile extends Component
{
    public $file;

    public $column_name;

    public $options;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($file, $column_name = null, $options = null)
    {
        $this->file = $file;
        $this->column_name = ! empty($column_name) ? $column_name : 'attachment';
        $this->options = ! empty($options) ? $options : false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.media.file');
    }
}
