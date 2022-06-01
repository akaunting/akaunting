<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;

class Ddefault extends Component
{
    public $id;

    /**
     * Tooltip position.
     *
     * @var string
     */
    public $position;

    /**
     * Tooltip in default icon.
     *
     * @var string
     */
    public $icon;

    /**
     * Tooltip in default icon.
     *
     * @var string
     */
    public $iconType;

    /**
     * defaultd text type name.
     *
     * @var string
     */
    public $text;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $position = 'right', $icon = 'lock', $iconType = '-round', $text = ''
    ) {
        $this->id = 'tooltip-default-' . mt_rand(1, time());
        $this->position = $position;
        $this->icon = $icon;
        $this->iconType = $iconType;
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.default');
    }
}
