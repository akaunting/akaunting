<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Component;

class ItemColumns extends Component
{
    /*  string */
    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $type = 'invoice')
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
        return view('components.documents.form.item-columns');
    }
}
