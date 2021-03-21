<?php

namespace Dingo\Api\Routing\Adapter;

use Dingo\Api\Contract\Routing\Adapter;
use Dingo\Api\Exception\UnknownVersionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;

class Laravel implements Adapter
{
    /**
     * Application container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Laravel router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Array of registered routes.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Array of merged old routes and API routes.
     *
     * @var array
     */
    protected $mergedRoutes = [];

    /**
     * Routes already defined on the router.
     *
     * @var \Illuminate\Routing\RouteCollection
     */
    protected $oldRoutes;

    /**
     * The globally available parameter patterns.
     *
     * @var array
     */
    protected $patterns = [];

    /**
     * Create a new laravel routing adapter instance.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Dispatch a request.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $version
     *
     * @return mixed
     */
    public function dispatch(Request $request, $version)
    {
        if (! isset($this->routes[$version])) {
            throw new UnknownVersionException;
        }

        $routes = $this->mergeOldRoutes($version);

        $this->router->setRoutes($routes);

        $router = clone $this->router;

        $response = $router->dispatch($request);

        unset($router);

        return $response;
    }

    /**
     * Merge the old application routes with the API routes.
     *
     * @param string $version
     *
     * @return array
     */
    protected function mergeOldRoutes($version)
    {
        if (! isset($this->oldRoutes)) {
            $this->oldRoutes = $this->router->getRoutes();
        }

        if (! isset($this->mergedRoutes[$version])) {
            $this->mergedRoutes[$version] = $this->routes[$version];

            foreach ($this->oldRoutes as $route) {
                $this->mergedRoutes[$version]->add($route);
            }

            $this->mergedRoutes[$version]->refreshNameLookups();
            $this->mergedRoutes[$version]->refreshActionLookups();
        }

        return $this->mergedRoutes[$version];
    }

    /**
     * Get the URI, methods, and action from the route.
     *
     * @param mixed                    $route
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getRouteProperties($route, Request $request)
    {
        if (method_exists($route, 'uri') && method_exists($route, 'methods')) {
            return [$route->uri(), $route->methods(), $route->getAction()];
        }

        return [$route->getUri(), $route->getMethods(), $route->getAction()];
    }

    /**
     * Add a route to the appropriate route collection.
     *
     * @param array  $methods
     * @param array  $versions
     * @param string $uri
     * @param mixed  $action
     *
     * @return \Illuminate\Routing\Route
     */
    public function addRoute(array $methods, array $versions, $uri, $action)
    {
        $this->createRouteCollections($versions);

        // Add where-patterns from original laravel router
        $action['where'] = array_merge($this->router->getPatterns(), $action['where'] ?? []);

        $route = new Route($methods, $uri, $action);
        $route->where($action['where']);

        foreach ($versions as $version) {
            $this->routes[$version]->add($route);
        }

        return $route;
    }

    /**
     * Create the route collections for the versions.
     *
     * @param array $versions
     *
     * @return void
     */
    protected function createRouteCollections(array $versions)
    {
        foreach ($versions as $version) {
            if (! isset($this->routes[$version])) {
                $this->routes[$version] = new RouteCollection;
            }
        }
    }

    /**
     * Get all routes or only for a specific version.
     *
     * @param string $version
     *
     * @return mixed
     */
    public function getRoutes($version = null)
    {
        if (! is_null($version)) {
            return $this->routes[$version];
        }

        return $this->routes;
    }

    /**
     * Get a normalized iterable set of routes.
     *
     * @param string $version
     *
     * @return mixed
     */
    public function getIterableRoutes($version = null)
    {
        return $this->getRoutes($version);
    }

    /**
     * Set the routes on the adapter.
     *
     * @param array $routes
     *
     * @return void
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Prepare a route for serialization.
     *
     * @param mixed $route
     *
     * @return mixed
     */
    public function prepareRouteForSerialization($route)
    {
        $route->prepareForSerialization();

        return $route;
    }

    /**
     * Gather the route middlewares.
     *
     * @param \Illuminate\Routing\Route $route
     *
     * @return array
     */
    public function gatherRouteMiddlewares($route)
    {
        if (method_exists($this->router, 'gatherRouteMiddleware')) {
            return $this->router->gatherRouteMiddleware($route);
        }

        return $this->router->gatherRouteMiddlewares($route);
    }

    /**
     * Get the Laravel router instance.
     *
     * @return \Illuminate\Routing\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Get the global "where" patterns.
     *
     * @return array
     */
    public function getPatterns()
    {
        return $this->patterns;
    }
}
