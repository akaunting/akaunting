<?php

namespace Dingo\Api\Routing;

use Closure;
use Dingo\Api\Contract\Debug\ExceptionHandler;
use Dingo\Api\Contract\Routing\Adapter;
use Dingo\Api\Http\InternalRequest;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class Router
{
    /**
     * Routing adapter instance.
     *
     * @var \Dingo\Api\Contract\Routing\Adapter
     */
    protected $adapter;

    /**
     * Accept parser instance.
     *
     * @var \Dingo\Api\Http\Parser\Accept
     */
    protected $accept;

    /**
     * Exception handler instance.
     *
     * @var \Dingo\Api\Contract\Debug\ExceptionHandler
     */
    protected $exception;

    /**
     * Application container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Group stack array.
     *
     * @var array
     */
    protected $groupStack = [];

    /**
     * Indicates if the request is conditional.
     *
     * @var bool
     */
    protected $conditionalRequest = true;

    /**
     * The current route being dispatched.
     *
     * @var \Dingo\Api\Routing\Route
     */
    protected $currentRoute;

    /**
     * The number of routes dispatched.
     *
     * @var int
     */
    protected $routesDispatched = 0;

    /**
     * The API domain.
     *
     * @var string
     */
    protected $domain;

    /**
     * The API prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Create a new router instance.
     *
     * @param \Dingo\Api\Contract\Routing\Adapter        $adapter
     * @param \Dingo\Api\Contract\Debug\ExceptionHandler $exception
     * @param \Illuminate\Container\Container            $container
     * @param string                                     $domain
     * @param string                                     $prefix
     *
     * @return void
     */
    public function __construct(Adapter $adapter, ExceptionHandler $exception, Container $container, $domain, $prefix)
    {
        $this->adapter = $adapter;
        $this->exception = $exception;
        $this->container = $container;
        $this->domain = $domain;
        $this->prefix = $prefix;
    }

    /**
     * An alias for calling the group method, allows a more fluent API
     * for registering a new API version group with optional
     * attributes and a required callback.
     *
     * This method can be called without the third parameter, however,
     * the callback should always be the last parameter.
     *
     * @param array|string   $version
     * @param array|callable $second
     * @param callable       $third
     *
     * @return void
     */
    public function version($version, $second, $third = null)
    {
        if (func_num_args() == 2) {
            [$version, $callback, $attributes] = array_merge(func_get_args(), [[]]);
        } else {
            [$version, $attributes, $callback] = func_get_args();
        }

        $attributes = array_merge($attributes, ['version' => $version]);

        $this->group($attributes, $callback);
    }

    /**
     * Create a new route group.
     *
     * @param array    $attributes
     * @param callable $callback
     *
     * @return void
     */
    public function group(array $attributes, $callback)
    {
        if (! isset($attributes['conditionalRequest'])) {
            $attributes['conditionalRequest'] = $this->conditionalRequest;
        }

        $attributes = $this->mergeLastGroupAttributes($attributes);

        if (! isset($attributes['version'])) {
            throw new RuntimeException('A version is required for an API group definition.');
        } else {
            $attributes['version'] = (array) $attributes['version'];
        }

        if ((! isset($attributes['prefix']) || empty($attributes['prefix'])) && isset($this->prefix)) {
            $attributes['prefix'] = $this->prefix;
        }

        if ((! isset($attributes['domain']) || empty($attributes['domain'])) && isset($this->domain)) {
            $attributes['domain'] = $this->domain;
        }

        $this->groupStack[] = $attributes;

        call_user_func($callback, $this);

        array_pop($this->groupStack);
    }

    /**
     * Create a new GET route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function get($uri, $action)
    {
        return $this->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    /**
     * Create a new POST route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function post($uri, $action)
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Create a new PUT route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function put($uri, $action)
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Create a new PATCH route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function patch($uri, $action)
    {
        return $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * Create a new DELETE route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function delete($uri, $action)
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Create a new OPTIONS route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function options($uri, $action)
    {
        return $this->addRoute('OPTIONS', $uri, $action);
    }

    /**
     * Create a new route that responding to all verbs.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function any($uri, $action)
    {
        $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'];

        return $this->addRoute($verbs, $uri, $action);
    }

    /**
     * Create a new route with the given verbs.
     *
     * @param array|string          $methods
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function match($methods, $uri, $action)
    {
        return $this->addRoute(array_map('strtoupper', (array) $methods), $uri, $action);
    }

    /**
     * Register an array of resources.
     *
     * @param array $resources
     *
     * @return void
     */
    public function resources(array $resources)
    {
        foreach ($resources as $name => $resource) {
            $options = [];

            if (is_array($resource)) {
                [$resource, $options] = $resource;
            }

            $this->resource($name, $resource, $options);
        }
    }

    /**
     * Register a resource controller.
     *
     * @param string $name
     * @param string $controller
     * @param array  $options
     *
     * @return void
     */
    public function resource($name, $controller, array $options = [])
    {
        if ($this->container->bound(ResourceRegistrar::class)) {
            $registrar = $this->container->make(ResourceRegistrar::class);
        } else {
            $registrar = new ResourceRegistrar($this);
        }

        $registrar->register($name, $controller, $options);
    }

    /**
     * Add a route to the routing adapter.
     *
     * @param string|array          $methods
     * @param string                $uri
     * @param string|array|callable $action
     *
     * @return mixed
     */
    public function addRoute($methods, $uri, $action)
    {
        if (is_string($action)) {
            $action = ['uses' => $action, 'controller' => $action];
        } elseif ($action instanceof Closure) {
            $action = [$action];
        } elseif (is_array($action)) {
            // For this sort of syntax $api->post('login', [LoginController::class, 'login']);
            if (is_string(Arr::first($action)) && class_exists(Arr::first($action)) && count($action) == 2) {
                $action = implode('@', $action);
                $action = ['uses' => $action, 'controller' => $action];
            }
        }

        $action = $this->mergeLastGroupAttributes($action);

        $action = $this->addControllerMiddlewareToRouteAction($action);

        $uri = $uri === '/' ? $uri : '/'.trim($uri, '/');

        if (! empty($action['prefix'])) {
            $uri = '/'.ltrim(rtrim(trim($action['prefix'], '/').'/'.trim($uri, '/'), '/'), '/');

            unset($action['prefix']);
        }

        $action['uri'] = $uri;

        return $this->adapter->addRoute((array) $methods, $action['version'], $uri, $action);
    }

    /**
     * Add the controller preparation middleware to the beginning of the routes middleware.
     *
     * @param array $action
     *
     * @return array
     */
    protected function addControllerMiddlewareToRouteAction(array $action)
    {
        array_unshift($action['middleware'], 'api.controllers');

        return $action;
    }

    /**
     * Merge the last groups attributes.
     *
     * @param array $attributes
     *
     * @return array
     */
    protected function mergeLastGroupAttributes(array $attributes)
    {
        if (empty($this->groupStack)) {
            return $this->mergeGroup($attributes, []);
        }

        return $this->mergeGroup($attributes, end($this->groupStack));
    }

    /**
     * Merge the given group attributes.
     *
     * @param array $new
     * @param array $old
     *
     * @return array
     */
    protected function mergeGroup(array $new, array $old)
    {
        $new['namespace'] = $this->formatNamespace($new, $old);

        $new['prefix'] = $this->formatPrefix($new, $old);

        foreach (['middleware', 'providers', 'scopes', 'before', 'after'] as $option) {
            $new[$option] = $this->formatArrayBasedOption($option, $new);
        }

        if (isset($new['domain'])) {
            unset($old['domain']);
        }

        if (isset($new['conditionalRequest'])) {
            unset($old['conditionalRequest']);
        }

        if (isset($new['uses'])) {
            $new['uses'] = $this->formatUses($new, $old);
        }

        $new['where'] = array_merge(Arr::get($old, 'where', []), Arr::get($new, 'where', []));

        if (isset($old['as'])) {
            $new['as'] = trim($old['as'].'.'.Arr::get($new, 'as', ''), '.');
        }

        return array_merge_recursive(Arr::except($old, ['namespace', 'prefix', 'where', 'as']), $new);
    }

    /**
     * Format an array based option in a route action.
     *
     * @param string $option
     * @param array  $new
     *
     * @return array
     */
    protected function formatArrayBasedOption($option, array $new)
    {
        $value = Arr::get($new, $option, []);

        return is_string($value) ? explode('|', $value) : $value;
    }

    /**
     * Format the uses key in a route action.
     *
     * @param array $new
     * @param array $old
     *
     * @return string
     */
    protected function formatUses(array $new, array $old)
    {
        if (isset($old['namespace']) && is_string($new['uses']) && strpos($new['uses'], '\\') !== 0) {
            return $old['namespace'].'\\'.$new['uses'];
        }

        return $new['uses'];
    }

    /**
     * Format the namespace for the new group attributes.
     *
     * @param array $new
     * @param array $old
     *
     * @return string
     */
    protected function formatNamespace(array $new, array $old)
    {
        if (isset($new['namespace']) && isset($old['namespace'])) {
            return trim($old['namespace'], '\\').'\\'.trim($new['namespace'], '\\');
        } elseif (isset($new['namespace'])) {
            return trim($new['namespace'], '\\');
        }

        return Arr::get($old, 'namespace');
    }

    /**
     * Format the prefix for the new group attributes.
     *
     * @param array $new
     * @param array $old
     *
     * @return string
     */
    protected function formatPrefix($new, $old)
    {
        if (isset($new['prefix'])) {
            return trim(Arr::get($old, 'prefix'), '/').'/'.trim($new['prefix'], '/');
        }

        return Arr::get($old, 'prefix', '');
    }

    /**
     * Dispatch a request via the adapter.
     *
     * @param \Dingo\Api\Http\Request $request
     *
     * @throws \Exception
     *
     * @return \Dingo\Api\Http\Response
     */
    public function dispatch(Request $request)
    {
        $this->currentRoute = null;

        $this->container->instance(Request::class, $request);

        $this->routesDispatched++;

        try {
            $response = $this->adapter->dispatch($request, $request->version());
        } catch (Exception $exception) {
            if ($request instanceof InternalRequest) {
                throw $exception;
            }

            $this->exception->report($exception);

            $response = $this->exception->handle($exception);
        }

        return $this->prepareResponse($response, $request, $request->format());
    }

    /**
     * Prepare a response by transforming and formatting it correctly.
     *
     * @param mixed                   $response
     * @param \Dingo\Api\Http\Request $request
     * @param string                  $format
     *
     * @return \Dingo\Api\Http\Response
     */
    protected function prepareResponse($response, Request $request, $format)
    {
        if ($response instanceof IlluminateResponse) {
            $response = Response::makeFromExisting($response);
        } elseif ($response instanceof JsonResponse) {
            $response = Response::makeFromJson($response);
        }

        if ($response instanceof Response) {
            // If we try and get a formatter that does not exist we'll let the exception
            // handler deal with it. At worst we'll get a generic JSON response that
            // a consumer can hopefully deal with. Ideally they won't be using
            // an unsupported format.
            try {
                $response->getFormatter($format)->setResponse($response)->setRequest($request);
            } catch (NotAcceptableHttpException $exception) {
                return $this->exception->handle($exception);
            }

            $response = $response->morph($format);
        }

        if ($response->isSuccessful() && $this->requestIsConditional()) {
            if (! $response->headers->has('ETag')) {
                $response->setEtag(sha1($response->getContent() ?: ''));
            }

            $response->isNotModified($request);
        }

        return $response;
    }

    /**
     * Gather the middleware for the given route.
     *
     * @param mixed $route
     *
     * @return array
     */
    public function gatherRouteMiddlewares($route)
    {
        return $this->adapter->gatherRouteMiddlewares($route);
    }

    /**
     * Determine if the request is conditional.
     *
     * @return bool
     */
    protected function requestIsConditional()
    {
        return $this->getCurrentRoute()->requestIsConditional();
    }

    /**
     * Set the conditional request.
     *
     * @param bool $conditionalRequest
     *
     * @return void
     */
    public function setConditionalRequest($conditionalRequest)
    {
        $this->conditionalRequest = $conditionalRequest;
    }

    /**
     * Get the current request instance.
     *
     * @return \Dingo\Api\Http\Request
     */
    public function getCurrentRequest()
    {
        return $this->container['request'];
    }

    /**
     * Get the current route instance.
     *
     * @return \Dingo\Api\Routing\Route
     */
    public function getCurrentRoute()
    {
        if (isset($this->currentRoute)) {
            return $this->currentRoute;
        } elseif (! $this->hasDispatchedRoutes() || ! $route = $this->container['request']->route()) {
            return;
        }

        return $this->currentRoute = $this->createRoute($route);
    }

    /**
     * Get the currently dispatched route instance.
     *
     * @return \Illuminate\Routing\Route
     */
    public function current()
    {
        return $this->getCurrentRoute();
    }

    /**
     * Create a new route instance from an adapter route.
     *
     * @param array|\Illuminate\Routing\Route $route
     *
     * @return \Dingo\Api\Routing\Route
     */
    public function createRoute($route)
    {
        return new Route($this->adapter, $this->container, $this->container['request'], $route);
    }

    /**
     * Set the current route instance.
     *
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return void
     */
    public function setCurrentRoute(Route $route)
    {
        $this->currentRoute = $route;
    }

    /**
     * Determine if the router has a group stack.
     *
     * @return bool
     */
    public function hasGroupStack()
    {
        return ! empty($this->groupStack);
    }

    /**
     * Get the prefix from the last group on the stack.
     *
     * @return string
     */
    public function getLastGroupPrefix()
    {
        if (empty($this->groupStack)) {
            return '';
        }

        $group = end($this->groupStack);

        return $group['prefix'];
    }

    /**
     * Get all routes registered on the adapter.
     *
     * @param string $version
     *
     * @return mixed
     */
    public function getRoutes($version = null)
    {
        $routes = $this->adapter->getIterableRoutes($version);

        if (! is_null($version)) {
            $routes = [$version => $routes];
        }

        $collections = [];

        foreach ($routes as $key => $value) {
            $collections[$key] = new RouteCollection($this->container['request']);

            foreach ($value as $route) {
                $route = $this->createRoute($route);

                $collections[$key]->add($route);
            }
        }

        return is_null($version) ? $collections : $collections[$version];
    }

    /**
     * Get the raw adapter routes.
     *
     * @return array
     */
    public function getAdapterRoutes()
    {
        return $this->adapter->getRoutes();
    }

    /**
     * Set the raw adapter routes.
     *
     * @param array $routes
     *
     * @return void
     */
    public function setAdapterRoutes(array $routes)
    {
        $this->adapter->setRoutes($routes);

        $this->container->instance('api.routes', $this->getRoutes());
    }

    /**
     * Get the number of routes dispatched.
     *
     * @return int
     */
    public function getRoutesDispatched()
    {
        return $this->routesDispatched;
    }

    /**
     * Determine if the router has dispatched any routes.
     *
     * @return bool
     */
    public function hasDispatchedRoutes()
    {
        return $this->routesDispatched > 0;
    }

    /**
     * Get the current route name.
     *
     * @return string|null
     */
    public function currentRouteName()
    {
        return $this->current() ? $this->current()->getName() : null;
    }

    /**
     * Alias for the "currentRouteNamed" method.
     *
     * @param mixed string
     *
     * @return bool
     */
    public function is()
    {
        foreach (func_get_args() as $pattern) {
            if (Str::is($pattern, $this->currentRouteName())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the current route matches a given name.
     *
     * @param string $name
     *
     * @return bool
     */
    public function currentRouteNamed($name)
    {
        return $this->current() ? $this->current()->getName() == $name : false;
    }

    /**
     * Get the current route action.
     *
     * @return string|null
     */
    public function currentRouteAction()
    {
        if (! $route = $this->current()) {
            return;
        }

        $action = $route->getAction();

        return is_string($action['uses']) ? $action['uses'] : null;
    }

    /**
     * Alias for the "currentRouteUses" method.
     *
     * @param  mixed  string
     *
     * @return bool
     */
    public function uses()
    {
        foreach (func_get_args() as $pattern) {
            if (Str::is($pattern, $this->currentRouteAction())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the current route action matches a given action.
     *
     * @param string $action
     *
     * @return bool
     */
    public function currentRouteUses($action)
    {
        return $this->currentRouteAction() == $action;
    }
}
