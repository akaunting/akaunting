<?php

namespace App\View\Components\Form;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class Section extends Component
{
    public $spacingVertical;

    public $spacingHorizontal;

    public $columnNumber;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $spacingVertical = 'gap-y-6', string $spacingHorizontal = 'gap-x-8', string $columnNumber = 'sm:grid-cols-6',
    ) {
        $this->spacingVertical = $spacingVertical;
        $this->spacingHorizontal = $spacingHorizontal;
        $this->columnNumber = $columnNumber;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.section.index');
    }
}
