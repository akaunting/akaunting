<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class Icon extends Component
{
    /** @var string */
    public $icon;

    /** @var string */
    public $class;

    /** @var bool */
    public $filled;

    /** @var bool */
    public $rounded;

    /** @var bool */
    public $sharp;

    /** @var bool */
    public $simpleIcons;

    /** @var bool */
    public $custom;

    /** @var string */
    public $alias;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $icon = '', string $class = '',
        bool $filled = false, bool $rounded = false, bool $sharp = false,
        bool $simpleIcons = false, bool $custom = false, string $alias = ''
    ) {
        $this->icon         = $icon;
        $this->class        = ($simpleIcons) ? 'w-8 h-8 ' . $class : $class;
        $this->filled       = $filled;
        $this->rounded      = $rounded;
        $this->sharp        = $sharp;
        $this->simpleIcons  = $simpleIcons;
        $this->custom       = $custom;
        $this->alias        = $alias;

        if ($custom) {
            $this->icon = $this->getCustomIcon($icon, $alias);
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.icon');
    }

    protected function getCustomIcon($icon, $alias)
    {
        $slug = Str::replace('custom-', '', $icon);

        $base_path = 'public/img/icons/';

        if (! empty($alias)) {
            $base_path = 'modules/' . Str::studly($alias) . '/Resources/assets/img/icons/';
        }

        $path = base_path($base_path . $slug . '.svg');

        if (! file_exists($path)) {
            $path = 'public/img/akaunting-logo-purple.svg';
        }

        return $path;
    }
}
