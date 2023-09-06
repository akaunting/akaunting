<?php

namespace Akaunting\Menu;

use Countable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Support\Arr;

class MenuBuilder implements Countable
{
    /**
     * Menu name.
     *
     * @var string
     */
    protected $menu;

    /**
     * Array menu items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Default presenter class.
     *
     * @var string
     */
    protected $presenter = Presenters\Bootstrap3\Navbar::class;

    /**
     * Style name for each presenter.
     *
     * @var array
     */
    protected $styles = [];

    /**
     * Prefix URL.
     *
     * @var string|null
     */
    protected $prefixUrl;

    /**
     * The URL fragment to add to all URLs.
     *
     * @var string|null
     */
    protected $fragmentUrl;

    /**
     * The name of view presenter.
     *
     * @var string
     */
    protected $view;

    /**
     * The laravel view factory instance.
     *
     * @var \Illuminate\View\Factory
     */
    protected $views;

    /**
     * Determine whether the ordering feature is enabled or not.
     *
     * @var boolean
     */
    protected $ordering = false;

    /**
     * Resolved item binding map.
     *
     * @var array
     */
    protected $bindings = [];
    /**
     * @var Repository
     */
    private $config;

    /**
     * Constructor.
     *
     * @param string $menu
     * @param Repository $config
     */
    public function __construct($menu, Repository $config)
    {
        $this->menu = $menu;
        $this->config = $config;
    }

    /**
     * Get menu name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->menu;
    }

    /**
     * Find menu item by title.
     *
     * @param  string        $title
     * @param  callable|null $callback
     * @return mixed
     */
    public function whereTitle($title, callable $callback = null)
    {
        $item = $this->findBy('title', $title);

        if (is_callable($callback)) {
            return call_user_func($callback, $item);
        }

        return $item;
    }

    /**
     * Find menu item by key and value.
     *
     * @param  string $key
     * @param  string $value
     * @return \Akaunting\Menu\MenuItem
     */
    public function findBy($key, $value)
    {
        return collect($this->items)->filter(function ($item) use ($key, $value) {
            return $item->{$key} == $value;
        })->first();
    }

    /**
     * Remove menu item by title.
     *
     * @param  string $title
     * @return void
     */
    public function removeByTitle($title)
    {
        $this->removeBy('title', $title);
    }

    /**
     * Remove menu item by key and value.
     *
     * @param  string $key
     * @param  string $value
     * @return void
     */
    public function removeBy($key, $value)
    {
        $this->items = collect($this->items)->reject(function ($item) use ($key, $value) {
            return $item->{$key} == $value;
        })->values()->all();
    }

    /**
     * Set view factory instance.
     *
     * @param ViewFactory $views
     *
     * @return $this
     */
    public function setViewFactory(ViewFactory $views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Set view.
     *
     * @param string $view
     *
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Set Prefix URL.
     *
     * @param string $prefixUrl
     *
     * @return $this
     */
    public function setPrefixUrl($prefixUrl)
    {
        $this->prefixUrl = $prefixUrl;

        return $this;
    }

    /**
     * Set Prefix URL.
     *
     * @param string $fragmentUrl
     *
     * @return $this
     */
    public function setFragmentUrl($fragmentUrl)
    {
        $this->fragmentUrl = $fragmentUrl;

        return $this;
    }

    /**
     * Set styles.
     *
     * @param array $styles
     */
    public function setStyles(array $styles)
    {
        $this->styles = $styles;
    }

    /**
     * Set new presenter class.
     *
     * @param string $presenter
     */
    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * Get presenter instance.
     *
     * @return \Akaunting\Menu\Presenters\PresenterInterface
     */
    public function getPresenter()
    {
        return new $this->presenter();
    }

    /**
     * Set new presenter class by given style name.
     *
     * @param string $name
     *
     * @return self
     */
    public function style($name)
    {
        if ($this->hasStyle($name)) {
            $this->setPresenter($this->getStyle($name));
        }

        return $this;
    }

    /**
     * Determine if the given name in the presenter style.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasStyle($name)
    {
        return array_key_exists($name, $this->getStyles());
    }

    /**
     * Get style aliases.
     *
     * @return mixed
     */
    public function getStyles()
    {
        return $this->styles ?: $this->config->get('menu.styles');
    }

    /**
     * Get the presenter class name by given alias name.
     *
     * @param $name
     *
     * @return mixed
     */
    public function getStyle($name)
    {
        $style = $this->getStyles();

        return $style[$name];
    }

    /**
     * Set new presenter class from given alias name.
     *
     * @param $name
     */
    public function setPresenterFromStyle($name)
    {
        $this->setPresenter($this->getStyle($name));
    }

    /**
     * Set the resolved item bindings
     *
     * @param array $bindings
     * @return $this
     */
    public function setBindings(array $bindings)
    {
        $this->bindings = $bindings;

        return $this;
    }

    /**
     * Resolves a key from the bindings array.
     *
     * @param  string|array $key
     * @return mixed
     */
    public function resolve($key)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $key[$k] = $this->resolve($v);
            }
        } elseif (is_string($key)) {
            $matches = [];

            preg_match_all('/{[\s]*?([^\s]+)[\s]*?}/i', $key, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                if (array_key_exists($match[1], $this->bindings)) {
                    $key = preg_replace('/' . $match[0] . '/', $this->bindings[$match[1]], $key, 1);
                }
            }
        }

        return $key;
    }

    /**
     * Resolves an array of menu items properties.
     *
     * @param  array  &$items
     * @return void
     */
    protected function resolveItems(array &$items)
    {
        $resolver = function ($property) {
            return $this->resolve($property) ?: $property;
        };

        $totalItems = count($items);
        for ($i = 0; $i < $totalItems; $i++) {
            $items[$i]->fill(array_map($resolver, $items[$i]->getProperties()));
        }
    }

    /**
     * Add new child menu.
     *
     * @param array $attributes
     *
     * @return \Akaunting\Menu\MenuItem
     */
    public function add(array $attributes = [])
    {
        $item = MenuItem::make($attributes);

        $this->items[] = $item;

        return $item;
    }

    /**
     * Create new menu with dropdown.
     *
     * @param $title
     * @param callable $callback
     * @param array    $attributes
     * @param string|null $fragment
     *
     * @return $this
     */
    public function dropdown($title, \Closure $callback, $order = null, array $attributes = [], $fragment = null)
    {
        $fragment = $fragment ?: $this->fragmentUrl;

        $properties = compact('title', 'order', 'attributes', 'fragment');

        if (func_num_args() == 3) {
            $arguments = func_get_args();

            $title = Arr::get($arguments, 0);
            $attributes = Arr::get($arguments, 2);

            $properties = compact('title', 'attributes');
        }

        $item = MenuItem::make($properties);

        call_user_func($callback, $item);

        $this->items[] = $item;

        return $item;
    }

    /**
     * Register new menu item using registered route.
     *
     * @param $route
     * @param $title
     * @param array $parameters
     * @param array $attributes
     * @param string|null $fragment
     *
     * @return static
     */
    public function route($route, $title, $parameters = [], $order = null, $attributes = [], $fragment = null)
    {
        if (func_num_args() == 4) {
            $arguments = func_get_args();

            return $this->add([
                'route' => [Arr::get($arguments, 0), Arr::get($arguments, 2)],
                'title' => Arr::get($arguments, 1),
                'attributes' => Arr::get($arguments, 3),
            ]);
        }

        $route = [$route, $parameters];

        $fragment = $fragment ?: $this->fragmentUrl;

        $item = MenuItem::make(compact('route', 'title', 'parameters', 'attributes', 'order', 'fragment'));

        $this->items[] = $item;

        return $item;
    }

    /**
     * Format URL.
     *
     * @param string $url
     *
     * @return string
     */
    protected function formatUrl($url)
    {
        $url = !is_null($this->prefixUrl) ? $this->prefixUrl . $url : $url;
        $url = !is_null($this->fragmentUrl) ? $url . '#' . $this->fragmentUrl : $url;

        return $url == '/' ? '/' : ltrim(rtrim($url, '/'), '/');
    }

    /**
     * Register new menu item using url.
     *
     * @param $url
     * @param $title
     * @param array $attributes
     * @param string|null $fragment
     *
     * @return static
     */
    public function url($url, $title, $order = 0, $attributes = [], $fragment = null)
    {
        if (func_num_args() == 3) {
            $arguments = func_get_args();

            return $this->add([
                'url' => $this->formatUrl(Arr::get($arguments, 0)),
                'title' => Arr::get($arguments, 1),
                'attributes' => Arr::get($arguments, 2),
            ]);
        }

        $url = $this->formatUrl($url);

        $fragment = $fragment ?: $this->fragmentUrl;

        $item = MenuItem::make(compact('url', 'title', 'order', 'attributes', 'fragment'));

        $this->items[] = $item;

        return $item;
    }

    /**
     * Add new divider item.
     *
     * @param int $order
     * @return \Akaunting\Menu\MenuItem
     */
    public function addDivider($order = null)
    {
        $this->items[] = new MenuItem([
            'name' => 'divider',
            'order' => $order,
        ]);

        return $this;
    }

    /**
     * Add new header item.
     *
     * @return \Akaunting\Menu\MenuItem
     */
    public function addHeader($title, $order = null)
    {
        $this->items[] = new MenuItem([
            'name' => 'header',
            'title' => $title,
            'order' => $order,
        ]);

        return $this;
    }

    /**
     * Alias for "addHeader" method.
     *
     * @param string $title
     *
     * @return $this
     */
    public function header($title)
    {
        return $this->addHeader($title);
    }

    /**
     * Alias for "addDivider" method.
     *
     * @return $this
     */
    public function divider()
    {
        return $this->addDivider();
    }

    /**
     * Get items count.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Empty the current menu items.
     */
    public function destroy()
    {
        $this->items = [];

        return $this;
    }

    /**
     * Render the menu to HTML tag.
     *
     * @param string $presenter
     *
     * @return string
     */
    public function render($presenter = null)
    {
        $this->resolveItems($this->items);

        if (!is_null($this->view)) {
            return $this->renderView($presenter);
        }

        if ($this->hasStyle($presenter)) {
            $this->setPresenterFromStyle($presenter);
        }

        if (!is_null($presenter) && !$this->hasStyle($presenter)) {
            $this->setPresenter($presenter);
        }

        return $this->renderMenu();
    }

    /**
     * Render menu via view presenter.
     *
     * @return \Illuminate\View\View
     */
    public function renderView($presenter = null)
    {
        return $this->views->make($presenter ?: $this->view, [
            'items' => $this->getOrderedItems(),
        ]);
    }

    /**
     * Get original items.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get menu items as laravel collection instance.
     *
     * @return \Illuminate\Support\Collection
     */
    public function toCollection()
    {
        return collect($this->items);
    }

    /**
     * Get menu items as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->toCollection()->toArray();
    }

    /**
     * Enable menu ordering.
     *
     * @return self
     */
    public function enableOrdering()
    {
        $this->ordering = true;

        return $this;
    }

    /**
     * Disable menu ordering.
     *
     * @return self
     */
    public function disableOrdering()
    {
        $this->ordering = false;

        return $this;
    }

    /**
     * Get menu items and order it by 'order' key.
     *
     * @return array
     */
    public function getOrderedItems()
    {
        if (config('menu.ordering') || $this->ordering) {
            return $this->toCollection()->sortBy(function ($item) {
                return $item->order;
            })->all();
        }

        return $this->items;
    }

    /**
     * Render the menu.
     *
     * @return string
     */
    protected function renderMenu()
    {
        $presenter = $this->getPresenter();
        $menu = $presenter->getOpenTagWrapper();

        foreach ($this->getOrderedItems() as $item) {
            if ($item->hidden()) {
                continue;
            }

            if ($item->hasSubMenu()) {
                $menu .= $presenter->getMenuWithDropDownWrapper($item);
            } elseif ($item->isHeader()) {
                $menu .= $presenter->getHeaderWrapper($item);
            } elseif ($item->isDivider()) {
                $menu .= $presenter->getDividerWrapper();
            } else {
                $menu .= $presenter->getMenuWithoutDropdownWrapper($item);
            }
        }

        $menu .= $presenter->getCloseTagWrapper();

        return $menu;
    }
}
