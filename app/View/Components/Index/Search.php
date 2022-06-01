<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;

class Search extends Component
{
    /**
     * The Currency currency.
     *
     * @var bool|string
     */
    public $searchString;

    /**
     * The Currency currency.
     *
     * @var bool|string
     */
    public $bulkAction;

    /**
     * The Currency currency.
     *
     * @var string
     */
    public $action;

    /**
     * The Currency currency.
     *
     * @var string
     */
    public $route;

    /**
     * The Currency currency.
     *
     * @var string
     */
    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $searchString = false, $bulkAction = false, $action = false, $route = false, $url = false
    ) {
        $this->searchString = $searchString;
        $this->bulkAction = $bulkAction;
        $this->action = $this->getAction($action, $route, $url);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.search');
    }

    protected function getAction($action, $route, $url)
    {
        if (! empty($action)) {
            return $action;
        }

        if (! empty($route)) {
            return $this->getRouteAction($route);
        }

        if (! empty($url)) {
            return $this->getUrlAction($url);
        }

        return '';
    }

    /**
     * Get the action for a "url" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getUrlAction($options)
    {
        if (is_array($options)) {
            return url($options[0], array_slice($options, 1));
        }

        return url($options);
    }

    /**
     * Get the action for a "route" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getRouteAction($options)
    {
        if (is_array($options)) {
            $parameters = array_slice($options, 1);

            if (array_keys($options) === [0, 1]) {
                $parameters = head($parameters);
            }

            return route($options[0], $parameters);
        }

        return route($options);
    }
}
