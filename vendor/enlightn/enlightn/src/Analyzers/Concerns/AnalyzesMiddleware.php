<?php

namespace Enlightn\Enlightn\Analyzers\Concerns;

use Closure;
use Exception;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Str;
use ReflectionClass;

trait AnalyzesMiddleware
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The HTTP kernel instance.
     *
     * @var \Illuminate\Contracts\Http\Kernel
     */
    protected $kernel;

    /**
     * Determine if the application uses the provided middleware.
     *
     * @param string $middlewareClass
     * @return bool
     * @throws \ReflectionException
     */
    protected function appUsesMiddleware(string $middlewareClass)
    {
        return $this->getAllMiddleware()->contains(function ($middleware) use ($middlewareClass) {
            return $middleware === $middlewareClass
                || (class_exists($middlewareClass) && is_subclass_of($middleware, $middlewareClass));
        });
    }

    /**
     * Compile a list of all middlewares used by the application.
     *
     * @return \Illuminate\Support\Collection
     * @throws \ReflectionException
     */
    protected function getAllMiddleware()
    {
        return $this->getAllRouteMiddleware()->merge($this->getGlobalMiddleware());
    }

    /**
     * Compile a list of all route middlewares used by the application.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAllRouteMiddleware()
    {
        return collect($this->router->getRoutes())->map(function ($route) {
            return $this->getMiddleware($route);
        })->flatten()->unique();
    }

    /**
     * Snatch the global middleware from the kernel instance using reflection witchcraft.
     * Kids, don't try this at home.
     *
     * No way around this as there's no method to get or register global middleware.
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getGlobalMiddleware()
    {
        $mirror = new ReflectionClass($this->kernel);
        $property = $mirror->getProperty('middleware');
        $property->setAccessible(true);

        return collect((array) $property->getValue($this->kernel))->map(function ($middleware) {
            // To get the middleware class names, we must separate the parameters.
            return Str::before($middleware, ':');
        })->toArray();
    }

    /**
     * Determine if the application uses the provided global HTTP middleware.
     *
     * @param  string  $middlewareClass
     * @return bool
     * @throws \ReflectionException
     */
    protected function appUsesGlobalMiddleware(string $middlewareClass)
    {
        return collect($this->getGlobalMiddleware())->contains(function ($middleware) use ($middlewareClass) {
            return $middleware === $middlewareClass
                || (class_exists($middlewareClass) && is_subclass_of($middleware, $middlewareClass));
        });
    }

    /**
     * Determine if the application uses the provided middleware.
     *
     * @param  \Illuminate\Routing\Route $route
     * @param  string  $middlewareClass
     * @return bool
     */
    protected function routeUsesMiddleware($route, string $middlewareClass)
    {
        return collect($this->getMiddleware($route))->contains(function ($middleware) use ($middlewareClass) {
            return $middleware === $middlewareClass
                || (class_exists($middlewareClass) && is_subclass_of($middleware, $middlewareClass));
        });
    }

    /**
     * Get the middleware for a route.
     *
     * @param \Illuminate\Routing\Route $route
     *
     * @return array
     */
    protected function getMiddleware($route)
    {
        return collect($this->router->gatherRouteMiddleware($route))->map(function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        })->map(function ($middleware) {
            // To get the middleware class names, we must separate the parameters.
            return Str::before($middleware, ':');
        })->toArray();
    }

    /**
     * Determine if the application uses the provided middleware class (by basename).
     *
     * @param \Illuminate\Routing\Route $route
     * @param string $basenameMiddlewareClass
     * @return bool
     */
    protected function routeUsesBasenameMiddleware($route, string $basenameMiddlewareClass)
    {
        return collect($this->getBasenameMiddlewareClasses($route))
                ->contains(function ($middleware) use ($basenameMiddlewareClass) {
                    return $middleware === $basenameMiddlewareClass;
                });
    }

    /**
     * Get the basename of the middleware classes for a route.
     *
     * @param \Illuminate\Routing\Route $route
     *
     * @return array
     */
    protected function getBasenameMiddlewareClasses($route)
    {
        return collect($this->getMiddleware($route))->map(function ($middleware) {
            return class_basename($middleware);
        })->toArray();
    }

    /**
     * Determine if the app is stateless.
     *
     * @return bool
     * @throws \ReflectionException
     */
    protected function appIsStateless()
    {
        // If the app doesn't start sessions, it is stateless
        return ! $this->appUsesMiddleware(StartSession::class);
    }

    /**
     * Determine if the app uses cookies.
     *
     * @return bool
     * @throws \ReflectionException
     */
    protected function appUsesCookies()
    {
        return $this->appUsesMiddleware(AddQueuedCookiesToResponse::class);
    }

    /**
     * Find the login route url. Returns null if not found.
     *
     * @return string|null
     */
    protected function findLoginRoute()
    {
        // First, we check to see if a guest path is provided. If yes, we return the corresponding URL.
        if (! is_null($guestPath = config('enlightn.guest_url'))) {
            return url($guestPath);
        }

        // Here we just return the login named route. By default, Laravel uses
        // the named route "login" for all its auth scaffolding packages.
        try {
            return route('login');
        } catch (Exception $e) {
            // Next, we try to search for the first route that has the guest middleware.
            $route = collect($this->router->getRoutes())->filter(function ($route) {
                return $this->routeUsesBasenameMiddleware($route, 'RedirectIfAuthenticated');
            })->first();

            if (! is_null($route)) {
                return url($route->uri());
            } else {
                // If all else fails, default to the root URL.
                return url('/');
            }
        }
    }
}
