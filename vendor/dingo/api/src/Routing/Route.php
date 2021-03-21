<?php

namespace Dingo\Api\Routing;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Container\Container;
use Dingo\Api\Contract\Routing\Adapter;

class Route extends \Illuminate\Routing\Route
{
    /**
     * Routing adapter instance.
     *
     * @var \Dingo\Api\Contract\Routing\Adapter
     */
    protected $adapter;

    /**
     * Array of versions this route will respond to.
     *
     * @var array
     */
    protected $versions;

    /**
     * Array of scopes for OAuth 2.0 authentication.
     *
     * @var array
     */
    protected $scopes;

    /**
     * Array of authentication providers.
     *
     * @var array
     */
    protected $authenticationProviders;

    /**
     * The rate limit for this route.
     *
     * @var int
     */
    protected $rateLimit;

    /**
     * The expiration time for any rate limit set on this rate.
     *
     * @var int
     */
    protected $rateExpiration;

    /**
     * The throttle used by the route, takes precedence over rate limits.
     *
     * @return string|\Dingo\Api\Contract\Http\RateLimit\Throttle
     */
    protected $throttle;

    /**
     * Controller class name.
     *
     * @var string
     */
    protected $controllerClass;

    /**
     * Indicates if the request is conditional.
     *
     * @var bool
     */
    protected $conditionalRequest = true;

    /**
     * Middleware applied to route.
     *
     * @var array
     */
    protected $middleware;
    private $route;

    /**
     * Create a new route instance.
     *
     * @param \Dingo\Api\Contract\Routing\Adapter $adapter
     * @param \Illuminate\Container\Container     $container
     * @param \Illuminate\Http\Request            $request
     * @param array|\Illuminate\Routing\Route     $route
     */
    public function __construct(Adapter $adapter, Container $container, Request $request, $route)
    {
        $this->adapter = $adapter;
        $this->container = $container;
        $this->route = $route;

        $this->setupRouteProperties($request, $route);

        parent::__construct($this->methods, $this->uri, $this->action);
    }

    /**
     * Setup the route properties.
     *
     * @param Request $request
     * @param         $route
     *
     * @return void
     */
    protected function setupRouteProperties(Request $request, $route)
    {
        [$this->uri, $this->methods, $this->action] = $this->adapter->getRouteProperties($route, $request);

        $this->versions = Arr::pull($this->action, 'version');
        $this->conditionalRequest = Arr::pull($this->action, 'conditionalRequest', true);
        $this->middleware = (array) Arr::pull($this->action, 'middleware', []);
        $this->throttle = Arr::pull($this->action, 'throttle');
        $this->scopes = Arr::pull($this->action, 'scopes', []);
        $this->authenticationProviders = Arr::pull($this->action, 'providers', []);
        $this->rateLimit = Arr::pull($this->action, 'limit', 0);
        $this->rateExpiration = Arr::pull($this->action, 'expires', 0);

        // Now that the default route properties have been set we'll go ahead and merge
        // any controller properties to fully configure the route.
        $this->mergeControllerProperties();

        // If we have a string based throttle then we'll new up an instance of the
        // throttle through the container.
        if (is_string($this->throttle)) {
            $this->throttle = $this->container->make($this->throttle);
        }
    }

    /**
     * Merge the controller properties onto the route properties.
     */
    protected function mergeControllerProperties()
    {
        if (isset($this->action['uses']) && is_string($this->action['uses']) && Str::contains($this->action['uses'],
                '@')) {
            $this->action['controller'] = $this->action['uses'];

            $this->makeControllerInstance();
        }

        if (! $this->controllerUsesHelpersTrait()) {
            return;
        }

        $controller = $this->getControllerInstance();

        $controllerMiddleware = [];

        if (method_exists($controller, 'getMiddleware')) {
            $controllerMiddleware = $controller->getMiddleware();
        } elseif (method_exists($controller, 'getMiddlewareForMethod')) {
            $controllerMiddleware = $controller->getMiddlewareForMethod($this->controllerMethod);
        }

        $this->middleware = array_merge($this->middleware, $controllerMiddleware);

        if ($property = $this->findControllerPropertyOptions('throttles')) {
            $this->throttle = $property['class'];
        }

        if ($property = $this->findControllerPropertyOptions('scopes')) {
            $this->scopes = array_merge($this->scopes, $property['scopes']);
        }

        if ($property = $this->findControllerPropertyOptions('authenticationProviders')) {
            $this->authenticationProviders = array_merge($this->authenticationProviders, $property['providers']);
        }

        if ($property = $this->findControllerPropertyOptions('rateLimit')) {
            $this->rateLimit = $property['limit'];
            $this->rateExpiration = $property['expires'];
        }
    }

    /**
     * Find the controller options and whether or not it will apply to this routes controller method.
     *
     * @param string $name
     *
     * @return array
     */
    protected function findControllerPropertyOptions($name)
    {
        $properties = [];

        foreach ($this->getControllerInstance()->{'get'.ucfirst($name)}() as $property) {
            if (isset($property['options']) && ! $this->optionsApplyToControllerMethod($property['options'])) {
                continue;
            }

            unset($property['options']);

            $properties = array_merge_recursive($properties, $property);
        }

        return $properties;
    }

    /**
     * Determine if a controller method is in an array of options.
     *
     * @param array $options
     *
     * @return bool
     */
    protected function optionsApplyToControllerMethod(array $options)
    {
        if (empty($options)) {
            return true;
        } elseif (isset($options['only']) && in_array($this->controllerMethod,
                $this->explodeOnPipes($options['only']))) {
            return true;
        } elseif (isset($options['except'])) {
            return ! in_array($this->controllerMethod, $this->explodeOnPipes($options['except']));
        } elseif (in_array($this->controllerMethod, $this->explodeOnPipes($options))) {
            return true;
        }

        return false;
    }

    /**
     * Explode a value on a pipe delimiter.
     *
     * @param string|array $value
     *
     * @return array
     */
    protected function explodeOnPipes($value)
    {
        return is_string($value) ? explode('|', $value) : $value;
    }

    /**
     * Determine if the controller instance uses the helpers trait.
     *
     * @return bool
     */
    protected function controllerUsesHelpersTrait()
    {
        if (! $controller = $this->getControllerInstance()) {
            return false;
        }

        $traits = [];

        do {
            $traits = array_merge(class_uses($controller, false), $traits);
        } while ($controller = get_parent_class($controller));

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, false), $traits);
        }

        return isset($traits[Helpers::class]);
    }

    /**
     * Get the routes controller instance.
     *
     * @return null|\Illuminate\Routing\Controller|\Laravel\Lumen\Routing\Controller
     */
    public function getControllerInstance()
    {
        return $this->controller;
    }

    /**
     * Make a new controller instance through the container.
     *
     * @return \Illuminate\Routing\Controller|\Laravel\Lumen\Routing\Controller
     */
    protected function makeControllerInstance()
    {
        [$this->controllerClass, $this->controllerMethod] = explode('@', $this->action['uses']);

        $this->container->instance($this->controllerClass,
            $this->controller = $this->container->make($this->controllerClass));

        return $this->controller;
    }

    /**
     * Determine if the route is protected.
     *
     * @return bool
     */
    public function isProtected()
    {
        if (isset($this->middleware['api.auth']) || in_array('api.auth', $this->middleware)) {
            if ($this->controller && isset($this->middleware['api.auth'])) {
                return $this->optionsApplyToControllerMethod($this->middleware['api.auth']);
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the route has a throttle.
     *
     * @return bool
     */
    public function hasThrottle()
    {
        return ! is_null($this->throttle);
    }

    /**
     * Get the route throttle.
     *
     * @return string|\Dingo\Api\Http\RateLimit\Throttle\Throttle
     */
    public function throttle()
    {
        return $this->throttle;
    }

    /**
     * Get the route throttle.
     *
     * @return string|\Dingo\Api\Http\RateLimit\Throttle\Throttle
     */
    public function getThrottle()
    {
        return $this->throttle;
    }

    /**
     * Get the route scopes.
     *
     * @return array
     */
    public function scopes()
    {
        return $this->scopes;
    }

    /**
     * Get the route scopes.
     *
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Check if route requires all scopes or any scope to be valid.
     *
     * @return bool
     */
    public function scopeStrict()
    {
        return Arr::get($this->action, 'scopeStrict', false);
    }

    /**
     * Get the route authentication providers.
     *
     * @return array
     */
    public function authenticationProviders()
    {
        return $this->authenticationProviders;
    }

    /**
     * Get the route authentication providers.
     *
     * @return array
     */
    public function getAuthenticationProviders()
    {
        return $this->authenticationProviders;
    }

    /**
     * Get the rate limit for this route.
     *
     * @return int
     */
    public function rateLimit()
    {
        return $this->rateLimit;
    }

    /**
     * Get the rate limit for this route.
     *
     * @return int
     */
    public function getRateLimit()
    {
        return $this->rateLimit;
    }

    /**
     * Get the rate limit expiration time for this route.
     *
     * @return int
     */
    public function rateLimitExpiration()
    {
        return $this->rateExpiration;
    }

    /**
     * Get the rate limit expiration time for this route.
     *
     * @return int
     */
    public function getRateLimitExpiration()
    {
        return $this->rateExpiration;
    }

    /**
     * Get the name of the route.
     *
     * @return string
     */
    public function getName()
    {
        return Arr::get($this->action, 'as', null);
    }

    /**
     * Determine if the request is conditional.
     *
     * @return bool
     */
    public function requestIsConditional()
    {
        return $this->conditionalRequest === true;
    }

    /**
     * Get the versions for the route.
     *
     * @return array
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * Get the versions for the route.
     *
     * @return array
     */
    public function versions()
    {
        return $this->getVersions();
    }

    /**
     * Get the URI associated with the route.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->uri();
    }

    /**
     * Get the URI associated with the route.
     *
     * @return string
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * Get the HTTP verbs the route responds to.
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods();
    }

    /**
     * Get the HTTP verbs the route responds to.
     *
     * @return array
     */
    public function methods()
    {
        return $this->methods;
    }

    /**
     * Determine if the route only responds to HTTP requests.
     *
     * @return bool
     */
    public function httpOnly()
    {
        return in_array('http', $this->action, true)
            || (array_key_exists('http', $this->action) && $this->action['http']);
    }

    /**
     * Determine if the route only responds to HTTPS requests.
     *
     * @return bool
     */
    public function httpsOnly()
    {
        return $this->secure();
    }

    /**
     * Determine if the route only responds to HTTPS requests.
     *
     * @return bool
     */
    public function secure()
    {
        return in_array('https', $this->action, true)
            || (array_key_exists('https', $this->action) && $this->action['https']);
    }

    /**
     * Return the middlewares for this route.
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }
}
