<?php

namespace Dingo\Api\Contract\Routing;

use Illuminate\Http\Request;

interface Adapter
{
    /**
     * Dispatch a request.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $version
     *
     * @return mixed
     */
    public function dispatch(Request $request, $version);

    /**
     * Get the URI, methods, and action from the route.
     *
     * @param mixed                    $route
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getRouteProperties($route, Request $request);

    /**
     * Add a route to the appropriate route collection.
     *
     * @param array  $methods
     * @param array  $versions
     * @param string $uri
     * @param mixed  $action
     *
     * @return void
     */
    public function addRoute(array $methods, array $versions, $uri, $action);

    /**
     * Get all routes or only for a specific version.
     *
     * @param string $version
     *
     * @return mixed
     */
    public function getRoutes($version = null);

    /**
     * Get a normalized iterable set of routes. Top level key must be a version with each
     * version containing iterable routes that can be consumed by the adapter.
     *
     * @param string $version
     *
     * @return mixed
     */
    public function getIterableRoutes($version = null);

    /**
     * Set the routes on the adapter.
     *
     * @param array $routes
     *
     * @return void
     */
    public function setRoutes(array $routes);

    /**
     * Prepare a route for serialization.
     *
     * @param mixed $route
     *
     * @return mixed
     */
    public function prepareRouteForSerialization($route);
}
