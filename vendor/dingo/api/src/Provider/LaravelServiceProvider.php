<?php

namespace Dingo\Api\Provider;

use ReflectionClass;
use Dingo\Api\Http\FormRequest;
use Illuminate\Routing\Redirector;
use Dingo\Api\Http\Middleware\Auth;
use Illuminate\Contracts\Http\Kernel;
use Dingo\Api\Event\RequestWasMatched;
use Dingo\Api\Http\Middleware\Request;
use Illuminate\Foundation\Application;
use Dingo\Api\Http\Middleware\RateLimit;
use Illuminate\Routing\ControllerDispatcher;
use Dingo\Api\Http\Middleware\PrepareController;
use Illuminate\Http\Request as IlluminateRequest;
use Dingo\Api\Routing\Adapter\Laravel as LaravelAdapter;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class LaravelServiceProvider extends DingoServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->publishes([realpath(__DIR__.'/../../config/api.php') => config_path('api.php')]);

        $kernel = $this->app->make(Kernel::class);

        $this->app[Request::class]->mergeMiddlewares(
            $this->gatherAppMiddleware($kernel)
        );

        $this->addRequestMiddlewareToBeginning($kernel);

        $this->app['events']->listen(RequestWasMatched::class, function (RequestWasMatched $event) {
            $this->replaceRouteDispatcher();

            $this->updateRouterBindings();
        });

        // Originally Validate FormRequest after resolving
        /* This casues the prepareForValidation() function to be called twice, and seemingly has no other benefit, see discussion at
        https://github.com/dingo/api/issues/1668
        This is already done by laravel service provider, and works with Dingo router
        $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
            $resolved->validateResolved();
        });
        */

        $this->app->resolving(FormRequest::class, function (FormRequest $request, Application $app) {
            $this->initializeRequest($request, $app['request']);

            $request->setContainer($app)->setRedirector($app->make(Redirector::class));
        });

        $this->addMiddlewareAlias('api.auth', Auth::class);
        $this->addMiddlewareAlias('api.throttle', RateLimit::class);
        $this->addMiddlewareAlias('api.controllers', PrepareController::class);
    }

    /**
     * Replace the route dispatcher.
     *
     * @return void
     */
    protected function replaceRouteDispatcher()
    {
        $this->app->singleton('illuminate.route.dispatcher', function ($app) {
            return new ControllerDispatcher($app['api.router.adapter']->getRouter(), $app);
        });
    }

    /**
     * Grab the bindings from the Laravel router and set them on the adapters
     * router.
     *
     * @return void
     */
    protected function updateRouterBindings()
    {
        foreach ($this->getRouterBindings() as $key => $binding) {
            $this->app['api.router.adapter']->getRouter()->bind($key, $binding);
        }
    }

    /**
     * Get the Laravel routers bindings.
     *
     * @return array
     */
    protected function getRouterBindings()
    {
        $property = (new ReflectionClass($this->app['router']))->getProperty('binders');
        $property->setAccessible(true);

        return $property->getValue($this->app['router']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->registerRouterAdapter();
    }

    /**
     * Register the router adapter.
     *
     * @return void
     */
    protected function registerRouterAdapter()
    {
        $this->app->singleton('api.router.adapter', function ($app) {
            return new LaravelAdapter($app['router']);
        });
    }

    /**
     * Add the request middleware to the beginning of the kernel.
     *
     * @param \Illuminate\Contracts\Http\Kernel $kernel
     *
     * @return void
     */
    protected function addRequestMiddlewareToBeginning(Kernel $kernel)
    {
        $kernel->prependMiddleware(Request::class);
    }

    /**
     * Register a short-hand name for a middleware. For compatibility
     * with Laravel < 5.4 check if aliasMiddleware exists since this
     * method has been renamed.
     *
     * @param string $name
     * @param string $class
     *
     * @return void
     */
    protected function addMiddlewareAlias($name, $class)
    {
        $router = $this->app['router'];

        if (method_exists($router, 'aliasMiddleware')) {
            return $router->aliasMiddleware($name, $class);
        }

        return $router->middleware($name, $class);
    }

    /**
     * Gather the application middleware besides this one so that we can send
     * our request through them, exactly how the developer wanted.
     *
     * @param \Illuminate\Contracts\Http\Kernel $kernel
     *
     * @return array
     */
    protected function gatherAppMiddleware(Kernel $kernel)
    {
        $property = (new ReflectionClass($kernel))->getProperty('middleware');
        $property->setAccessible(true);

        return $property->getValue($kernel);
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
