<?php

namespace Akaunting\Menu;

use Closure;
use Countable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\View\Factory;

class Menu implements Countable
{
    /**
     * The menu collections.
     *
     * @var array
     */
    protected $menu = array();
    /**
     * @var Repository
     */
    private $config;
    /**
     * @var Factory
     */
    private $views;

    /**
     * The constructor.
     *
     * @param Factory    $views
     * @param Repository $config
     */
    public function __construct(Factory $views, Repository $config)
    {
        $this->views = $views;
        $this->config = $config;
    }

    /**
     * Make new menu.
     *
     * @param string $name
     * @param Closure $callback
     *
     * @return \Akaunting\Menu\MenuBuilder
     */
    public function make($name, \Closure $callback)
    {
        return $this->create($name, $callback);
    }

    /**
     * Create new menu.
     *
     * @param string   $name
     * @param Callable $resolver
     *
     * @return \Akaunting\Menu\MenuBuilder
     */
    public function create($name, Closure $resolver)
    {
        $builder = new MenuBuilder($name, $this->config);

        $builder->setViewFactory($this->views);

        $this->menu[$name] = $builder;

        return $resolver($builder);
    }

    /**
     * Check if the menu exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->menu);
    }

    /**
     * Get instance of the given menu if exists.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function instance($name)
    {
        return $this->has($name) ? $this->menu[$name] : null;
    }

    /**
     * Modify a specific menu.
     *
     * @param  string   $name
     * @param  Closure  $callback
     * @return void
     */
    public function modify($name, Closure $callback)
    {
        $menu = collect($this->menu)->filter(function ($menu) use ($name) {
            return $menu->getName() == $name;
        })->first();

        $callback($menu);
    }

    /**
     * Render the menu tag by given name.
     *
     * @param string $name
     * @param string $presenter
     *
     * @return string|null
     */
    public function get($name, $presenter = null, $bindings = array())
    {
        return $this->has($name) ?
            $this->menu[$name]->setBindings($bindings)->render($presenter) : null;
    }

    /**
     * Render the menu tag by given name.
     *
     * @param $name
     * @param null $presenter
     *
     * @return string
     */
    public function render($name, $presenter = null, $bindings = array())
    {
        return $this->get($name, $presenter, $bindings);
    }

    /**
     * Get a stylesheet for enable multilevel menu.
     *
     * @return mixed
     */
    public function style()
    {
        return $this->views->make('menu::bootstrap3.style')->render();
    }

    /**
     * Get all menus.
     *
     * @return array
     */
    public function all()
    {
        return $this->menu;
    }

    /**
     * Count menus.
     *
     * @return int
     */
    public function count()
    {
        return count($this->menu);
    }

    /**
     * Empty the current menus.
     */
    public function destroy()
    {
        $this->menu = array();
    }
}
