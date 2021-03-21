<?php

namespace Dingo\Api\Provider;

use ReflectionClass;
use Laravel\Lumen\Application;
use Dingo\Api\Http\FormRequest;
use Laravel\Lumen\Http\Redirector;
use Dingo\Api\Http\Middleware\Auth;
use Dingo\Api\Http\Middleware\Request;
use Dingo\Api\Http\Middleware\RateLimit;
use FastRoute\Dispatcher\GroupCountBased;
use Dingo\Api\Http\Middleware\PrepareController;
use FastRoute\RouteParser\Std as StdRouteParser;
use Illuminate\Http\Request as IlluminateRequest;
use Dingo\Api\Routing\Adapter\Lumen as LumenAdapter;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use FastRoute\DataGenerator\GroupCountBased as GcbDataGenerator;

class LumenServiceProvider extends DingoServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->app->configure('api');

        $reflection = new ReflectionClass($this->app);

        $this->app[Request::class]->mergeMiddlewares(
            $this->gatherAppMiddleware($reflection)
        );

        $this->addRequestMiddlewareToBeginning($reflection);

        // Because Lumen sets the route resolver at a very weird point we're going to
        // have to use reflection whenever the request instance is rebound to
        // set the route resolver to get the current route.
        $this->app->rebinding(IlluminateRequest::class, function ($app, $request) {
            $request->setRouteResolver(function () use ($app) {
                $reflection = new ReflectionClass($app);

                $property = $reflection->getProperty('currentRoute');
                $property->setAccessible(true);

                return $property->getValue($app);
            });
        });

        // Validate FormRequest after resolving
        $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
            $resolved->validateResolved();
        });

        $this->app->resolving(FormRequest::class, function (FormRequest $request, Application $app) {
            $this->initializeRequest($request, $app['request']);

            $request->setContainer($app)->setRedirector($app->make(Redirector::class));
        });

        $this->app->routeMiddleware([
            'api.auth' => Auth::class,
            'api.throttle' => RateLimit::class,
            'api.controllers' => PrepareController::class,
        ]);
    }

    /**
     * Setup the configuration.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $this->app->configure('api');

        parent::setupConfig();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton('api.router.adapter', function ($app) {
            return new LumenAdapter($app, new StdRouteParser, new GcbDataGenerator, $this->getDispatcherResolver());
        });
    }

    /**
     * Get the dispatcher resolver callback.
     *
     * @return \Closure
     */
    protected function getDispatcherResolver()
    {
        return function ($routeCollector) {
            return new GroupCountBased($routeCollector->getData());
        };
    }

    /**
     * Add the request middleware to the beginning of the middleware stack on the
     * Lumen application instance.
     *
     * @param \ReflectionClass $reflection
     *
     * @return void
     */
    protected function addRequestMiddlewareToBeginning(ReflectionClass $reflection)
    {
        $property = $reflection->getProperty('middleware');
        $property->setAccessible(true);

        $middleware = $property->getValue($this->app);

        array_unshift($middleware, Request::class);

        $property->setValue($this->app, $middleware);
        $property->setAccessible(false);
    }

    /**
     * Gather the application middleware besides this one so that we can send
     * our request through them, exactly how the developer wanted.
     *
     * @param \ReflectionClass $reflection
     *
     * @return array
     */
    protected function gatherAppMiddleware(ReflectionClass $reflection)
    {
        $property = $reflection->getProperty('middleware');
        $property->setAccessible(true);

        $middleware = $property->getValue($this->app);

        return $middleware;
    }

    /**
     * Initialize the form request with data from the given request.
     *
     * @param FormRequest $form
     * @param IlluminateRequest $current
     *
     * @return void
     */
    protected function initializeRequest(FormRequest $form, IlluminateRequest $current)
    {
        $files = $current->files->all();

        $files = is_array($files) ? array_filter($files) : $files;

        $form->initialize(
            $current->query->all(),
            $current->request->all(),
            $current->attributes->all(),
            $current->cookies->all(),
            $files,
            $current->server->all(),
            $current->getContent()
        );

        $form->setJson($current->json());

        if ($session = $current->getSession()) {
            $form->setLaravelSession($session);
        }

        $form->setUserResolver($current->getUserResolver());

        $form->setRouteResolver($current->getRouteResolver());
    }
}
