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
     * The layouts pass the title and url from the attributes of the page's favorite slot, which Blade
     * has already escaped, so they are decoded here and the menu stores them as plain text.
     *
     * @return void
     */
    public function __construct(string $title, string $icon, $route = null, $url = null)
    {
        $this->title = htmlspecialchars_decode($title, ENT_QUOTES);
        $this->icon = $icon;
        $this->route = $route;
        $this->url = htmlspecialchars_decode((string) $url, ENT_QUOTES);
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
