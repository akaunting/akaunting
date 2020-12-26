<?php

namespace App\View\Components\Documents\Form;

use Illuminate\View\Component;

class Note extends Component
{
    /** @var string */
    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.form.note');
    }
}
