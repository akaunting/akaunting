<?php

namespace App\View\Components\Menu;

use App\Abstracts\View\Component;

class Favorite extends Component
{
    /** @var string */
    public $title;

    /** @var string */
    public $icon;

    public $route;

    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $title, string $icon, $route = null, $url = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->route = $route;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.menu.favorite');
    }
}
