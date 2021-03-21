<?php

namespace Dingo\Api\Http\Middleware;

use Closure;
use Exception;
use Dingo\Api\Routing\Router;
use Laravel\Lumen\Application;
use Illuminate\Pipeline\Pipeline;
use Dingo\Api\Http\RequestValidator;
use Dingo\Api\Event\RequestWasMatched;
use Dingo\Api\Http\Request as HttpRequest;
use Illuminate\Contracts\Container\Container;
use Dingo\Api\Contract\Debug\ExceptionHandler;
use Dingo\Api\Contract\Http\Request as RequestContract;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Debug\ExceptionHandler as LaravelExceptionHandler;

class Request
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Exception handler instance.
     *
     * @var \Dingo\Api\Contract\Debug\ExceptionHandler
     */
    protected $exception;

    /**
     * Router instance.
     *
     * @var \Dingo\Api\Routing\Router
     */
    protected $router;

    /**
     * HTTP validator instance.
     *
     * @var \Dingo\Api\Http\Validator
     */
    protected $validator;

    /**
     * Event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Array of middleware.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Create a new request middleware instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Dingo\Api\Contract\Debug\ExceptionHandler   $exception
     * @param \Dingo\Api\Routing\Router                    $router
     * @param \Dingo\Api\Http\RequestValidator             $validator
     * @param \Illuminate\Contracts\Events\Dispatcher      $events
     *
     * @return void
     */
    public function __construct(Container $app, ExceptionHandler $exception, Router $router, RequestValidator $validator, EventDispatcher $events)
    {
        $this->app = $app;
        $this->exception = $exception;
        $this->router = $router;
        $this->validator = $validator;
        $this->events = $events;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if ($this->validator->validateRequest($request)) {
                $this->app->singleton(LaravelExceptionHandler::class, function ($app) {
                    return $app[ExceptionHandler::class];
                });

                $request = $this->app->make(RequestContract::class)->createFromIlluminate($request);

                $this->events->dispatch(new RequestWasMatched($request, $this->app));

                return $this->sendRequestThroughRouter($request);
            }
        } catch (Exception $exception) {
            $this->exception->report($exception);

            return $this->exception->handle($exception);
        }

        return $next($request);
    }

    /**
     * Send the request through the Dingo router.
     *
     * @param \Dingo\Api\Http\Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    protected function sendRequestThroughRouter(HttpRequest $request)
    {
        $this->app->instance('request', $request);

        return (new Pipeline($this->app))->send($request)->through($this->middleware)->then(function ($request) {
            return $this->router->dispatch($request);
        });
    }

    /**
     * Call the terminate method on middlewares.
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        if (! ($request = $this->app['request']) instanceof HttpRequest) {
            return;
        }

        // Laravel's route middlewares can be terminated just like application
        // middleware, so we'll gather all the route middleware here.
        // On Lumen this will simply be an empty array as it does
        // not implement terminable route middleware.
        $middlewares = $this->gatherRouteMiddlewares($request);

        // Because of how middleware is executed on Lumen we'll need to merge in the
        // application middlewares now so that we can terminate them. Laravel does
        // not need this as it handles things a little more gracefully so it
        // can terminate the application ones itself.
        if (class_exists(Application::class, false)) {
            $middlewares = array_merge($middlewares, $this->middleware);
        }

        foreach ($middlewares as $middleware) {
            if ($middleware instanceof Closure) {
                continue;
            }

            [$name, $parameters] = $this->parseMiddleware($middleware);

            $instance = $this->app->make($name);

            if (method_exists($instance, 'terminate')) {
                $instance->terminate($request, $response);
            }
        }
    }

    /**
     * Parse a middleware string to get the name and parameters.
     *
     * @author Taylor Otwell
     *
     * @param string $middleware
     *
     * @return array
     */
    protected function parseMiddleware($middleware)
    {
        [$name, $parameters] = array_pad(explode(':', $middleware, 2), 2, []);

        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }

        return [$name, $parameters];
    }

    /**
     * Gather the middlewares for the route.
     *
     * @param \Dingo\Api\Http\Request $request
     *
     * @return array
     */
    protected function gatherRouteMiddlewares($request)
    {
        if ($route = $request->route()) {
            return $this->router->gatherRouteMiddlewares($route);
        }

        return [];
    }

    /**
     * Set the middlewares.
     *
     * @param array $middleware
     *
     * @return void
     */
    public function setMiddlewares(array $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Merge new middlewares onto the existing middlewares.
     *
     * @param array $middleware
     *
     * @return void
     */
    public function mergeMiddlewares(array $middleware)
    {
        $this->middleware = array_merge($this->middleware, $middleware);
    }
}
