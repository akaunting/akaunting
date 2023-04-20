<?php

namespace App\View\Components;

use App\Abstracts\View\Component;

class Alert extends Component
{
    /** @var bool */
    public $rounded;

    /** @var bool */
    public $border;

    /** @var string */
    public $color;

    /** @var string */
    public $icon;

    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var array */
    public $list;

    /** @var array */
    public $actions;

    /** @var bool */
    public $dismiss;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        bool $rounded = true, bool $border = false,
        
        string $color = '', string $icon = '',
        
        string $title = '', string $description = '',
        array $list = [], array $actions = [],
        bool $dismiss = false
    ) {
        $this->rounded = $rounded;
        $this->border = $border;

        $this->color = $this->getColor($color);
        $this->icon = $this->getIcon($icon);

        $this->title = $title;
        $this->description = $description;

        $this->list = $list;
        $this->actions = $actions;

        $this->dismiss = $dismiss;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.alert.index');
    }

    protected function getColor($color)
    {
        if (! empty($color)) {
            return $color;
        }

        $color = 'green';

        return $color;
    }

    protected function getIcon($icon)
    {
        if (! empty($icon)) {
            return $icon;
        }

        $icon = 'check_circle';

        return $icon;
    }
}
