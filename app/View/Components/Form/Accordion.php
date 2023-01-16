<?php

namespace App\View\Components\Form;

use App\Abstracts\View\Component;

class Accordion extends Component
{
    public $type;

    public $icon;

    public $open;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, string $icon = '', bool $open = false
    ) {
        $this->type = $type;
        $this->icon = $this->getIcon($icon);
        $this->open = $open;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.accordion.index');
    }

    protected function getIcon($icon)
    {
        if (! empty($icon)) {
            return $icon;
        }

        return 'expand_more';
    }
}
