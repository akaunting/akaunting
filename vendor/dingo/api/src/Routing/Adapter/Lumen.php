<?php

namespace Dingo\Api\Routing\Adapter;

use ArrayIterator;
use ReflectionClass;
use FastRoute\Dispatcher;
use FastRoute\RouteParser;
use Illuminate\Support\Str;
use FastRoute\DataGenerator;
use Illuminate\Http\Request;
use FastRoute\RouteCollector;
use Laravel\Lumen\Application;
use Dingo\Api\Contract\Routing\Adapter;
use Dingo\Api\Exception\UnknownVersionException;

class Lumen implements Adapter
{
    /**
     * Lumen application instance.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * FastRoute parser instance.
     *
     * @var \FastRoute\RouteParser
     */
    protected $parser;

    /**
     * FastRoute data generator instance.
     *
     * @var \FastRoute\DataGenerator
     */
    protected $generator;

    /**
     * FastRoute dispatcher resolver callback.
     *
     * @var callable
     */
    protected $dispatcherResolver;

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
     * Indicates if the middleware has been removed from the application instance.
     *
     * @var bool
     */
    protected $middlewareRemoved = false;

    /**
     * Create a new lumen adapter instance.
     *
     * @param \Laravel\Lumen\Application $app
     * @param \FastRoute\RouteParser     $parser
     * @param \FastRoute\DataGenerator   $generator
     * @param callable                   $dispatcherResolver
     *
     * @return void
     */
    public function __construct(Application $app, RouteParser $parser, DataGenerator $generator, callable $dispatcherResolver)
    {
        $this->app = $app;
        $this->parser = $parser;
        $this->generator = $generator;
        $this->dispatcherResolver = $dispatcherResolver;
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

        $this->removeMiddlewareFromApp();

        $routeCollector = $this->mergeOldRoutes($version);
        $dispatcher = call_user_func($this->dispatcherResolver, $routeCollector);

        $this->app->setDispatcher($dispatcher);

        $this->normalizeRequestUri($request);

        return $this->app->dispatch($request);
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
            $this->oldRoutes = $this->app->router->getRoutes();
        }
        if (! isset($this->mergedRoutes[$version])) {
            $this->mergedRoutes[$version] = $this->routes[$version];
            foreach ($this->oldRoutes as $route) {
                $this->mergedRoutes[$version]->addRoute($route['method'], $route['uri'], $route['action']);
            }
        }

        return $this->mergedRoutes[$version];
    }

    /**
     * Normalize the request URI so that Lumen can properly dispatch it.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function normalizeRequestUri(Request $request)
    {
        $query = $request->server->get('QUERY_STRING');

        $uri = '/'.trim(str_replace('?'.$query, '', $request->server->get('REQUEST_URI')), '/').($query ? '?'.$query : '');

        $request->server->set('REQUEST_URI', $uri);
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
        $uri = ltrim(isset($route['uri']) ? $route['uri'] : $request->getRequestUri(), '/');
        $methods = isset($route['methods']) ? $route['methods'] : (array) $request->getMethod();
        $action = (isset($route[1]) && is_array($route[1])) ? $route[1] : $route;

        if (in_array('GET', $methods) && ! in_array('HEAD', $methods)) {
            $methods[] = 'HEAD';
        }

        return [$uri, $methods, $action];
    }

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
    public function addRoute(array $methods, array $versions, $uri, $action)
    {
        $this->createRouteCollections($versions);

        foreach ($versions as $version) {
            foreach ($this->breakUriSegments($uri) as $uri) {
                $this->routes[$version]->addRoute($methods, $uri, $action);
            }
        }
    }

    /**
     * Break a URI that has optional segments into individual URIs.
     *
     * @param string $uri
     *
     * @return array
     */
    protected function breakUriSegments($uri)
    {
        if (! Str::contains($uri, '?}')) {
            return (array) $uri;
        }

        $segments = preg_split(
            '/\/(\{.*?\})/',
            preg_replace('/\{(.*?)\?\}/', '{$1}', $uri),
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        $uris = [];

        while ($segments) {
            $uris[] = implode('/', $segments);

            array_pop($segments);
        }

        return $uris;
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
                $this->routes[$version] = new RouteCollector($this->parser, clone $this->generator);
            }
        }
    }

    /**
     * Remove the global application middleware as it's run from this packages
     * Request middleware. Lumen runs middleware later in its life cycle
     * which results in some middleware being executed twice.
     *
     * @return void
     */
    protected function removeMiddlewareFromApp()
    {
        if ($this->middlewareRemoved) {
            return;
        }

        $this->middlewareRemoved = true;

        $reflection = new ReflectionClass($this->app);
        $property = $reflection->getProperty('middleware');
        $property->setAccessible(true);
        $oldMiddlewares = $property->getValue($this->app);
        $newMiddlewares = [];
        foreach ($oldMiddlewares as $middle) {
            if ((new ReflectionClass($middle))->hasMethod('terminate') && $middle != 'Dingo\Api\Http\Middleware\Request') {
                $newMiddlewares = array_merge($newMiddlewares, [$middle]);
            }
        }
        $property->setValue($this->app, $newMiddlewares);
        $property->setAccessible(false);
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
     * Get routes in an iterable form.
     *
     * @param string $version
     *
     * @return \ArrayIterator
     */
    public function getIterableRoutes($version = null)
    {
        $iterable = [];

        foreach ($this->getRoutes($version) as $version => $collector) {
            $routeData = $collector->getData();

            // The first element in the array are the static routes that do not have any parameters.
            foreach ($this->normalizeStaticRoutes($routeData[0]) as $method => $routes) {
                if ($method === 'HEAD') {
                    continue;
                }

                foreach ($routes as $route) {
                    $route['methods'] = $this->setRouteMethods($route, $method);

                    $iterable[$version][] = $route;
                }
            }

            // The second element is the more complicated regex routes that have parameters.
            foreach ($routeData[1] as $method => $routes) {
                if ($method === 'HEAD') {
                    continue;
                }

                foreach ($routes as $data) {
                    foreach ($data['routeMap'] as [$route, $parameters]) {
                        $route['methods'] = $this->setRouteMethods($route, $method);

                        $iterable[$version][] = $route;
                    }
                }
            }
        }

        return new ArrayIterator($iterable);
    }

    /**
     * Normalize the FastRoute static routes so they're the same across multiple versions.
     *
     * @param array $routes
     *
     * @return array
     */
    protected function normalizeStaticRoutes(array $routes)
    {
        foreach (array_keys($routes) as $key) {
            // If any of the keys are  an HTTP method then we are running on a newer version of
            // Lumen and FastRoute which means we can leave the routes as they are.
            if ($this->stringIsHttpMethod($key)) {
                return $routes;
            }
        }

        $normalized = [];

        // To normalize the routes we'll take the inner array which contains the routes method as the
        // key and make that the parent element on the array. We'll then add all routes for a
        // particular HTTP method as children of it by keying them to their URI.
        foreach ($routes as $uri => $value) {
            foreach ($value as $method => $route) {
                $normalized[$method][$uri] = $route;
            }
        }

        return $normalized;
    }

    /**
     * Determine if a string is an HTTP method.
     *
     * @param string $string
     *
     * @return bool
     */
    protected function stringIsHttpMethod($string)
    {
        $methods = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

        return in_array($string, $methods, true);
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
        // Route caching is not implemented for Lumen.
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
        // Route caching is not implemented for Lumen.
    }

    /**
     * Gather the route middlewares.
     *
     * @param array $route
     *
     * @return array
     */
    public function gatherRouteMiddlewares($route)
    {
        // Route middleware in Lumen is not terminated.
        return [];
    }

    /**
     * Append given method to the current route methods.
     *
     * @param array  $route
     * @param string $method
     *
     * @return array
     */
    private function setRouteMethods($route, $method)
    {
        return isset($route['methods'])
            ? array_push($route['methods'], $method)
            : [$method];
    }
}
