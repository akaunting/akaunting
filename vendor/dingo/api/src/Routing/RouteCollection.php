<?php

namespace Dingo\Api\Routing;

use Countable;
use ArrayIterator;
use IteratorAggregate;

class RouteCollection implements Countable, IteratorAggregate
{
    /**
     * Routes on the collection.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Lookup for named routes.
     *
     * @var array
     */
    protected $names = [];

    /**
     * Lookup for action routes.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Add a route to the collection.
     *
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return \Dingo\Api\Routing\Route
     */
    public function add(Route $route)
    {
        $this->routes[] = $route;

        $this->addLookups($route);

        return $route;
    }

    /**
     * Add route lookups.
     *
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return void
     */
    protected function addLookups(Route $route)
    {
        $action = $route->getAction();

        if (isset($action['as'])) {
            $this->names[$action['as']] = $route;
        }

        if (isset($action['controller'])) {
            $this->actions[$action['controller']] = $route;
        }
    }

    /**
     * Get a route by name.
     *
     * @param string $name
     *
     * @return \Dingo\Api\Routing\Route|null
     */
    public function getByName($name)
    {
        return isset($this->names[$name]) ? $this->names[$name] : null;
    }

    /**
     * Get a route by action.
     *
     * @param string $action
     *
     * @return \Dingo\Api\Routing\Route|null
     */
    public function getByAction($action)
    {
        return isset($this->actions[$action]) ? $this->actions[$action] : null;
    }

    /**
     * Get all routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->getRoutes());
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->getRoutes());
    }
}
