<?php

namespace Dingo\Api\Facade;

use Dingo\Api\Http\InternalRequest;
use Illuminate\Support\Facades\Facade;

class API extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api.dispatcher';
    }

    /**
     * Bind an exception handler.
     *
     * @param callable $callback
     *
     * @return void
     */
    public static function error(callable $callback)
    {
        return static::$app['api.exception']->register($callback);
    }

    /**
     * Register a class transformer.
     *
     * @param string          $class
     * @param string|\Closure $transformer
     *
     * @return \Dingo\Api\Transformer\Binding
     */
    public static function transform($class, $transformer)
    {
        return static::$app['api.transformer']->register($class, $transformer);
    }

    /**
     * Get the authenticator.
     *
     * @return \Dingo\Api\Auth\Auth
     */
    public static function auth()
    {
        return static::$app['api.auth'];
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Auth\GenericUser|\Illuminate\Database\Eloquent\Model
     */
    public static function user()
    {
        return static::$app['api.auth']->user();
    }

    /**
     * Determine if a request is internal.
     *
     * @return bool
     */
    public static function internal()
    {
        return static::$app['api.router']->getCurrentRequest() instanceof InternalRequest;
    }

    /**
     * Get the response factory to begin building a response.
     *
     * @return \Dingo\Api\Http\Response\Factory
     */
    public static function response()
    {
        return static::$app['api.http.response'];
    }

    /**
     * Get the API router instance.
     *
     * @return \Dingo\Api\Routing\Router
     */
    public static function router()
    {
        return static::$app['api.router'];
    }

    /**
     * Get the API route of the given name, and optionally specify the API version.
     *
     * @param string $routeName
     * @param string $apiVersion
     *
     * @return string
     */
    public static function route($routeName, $apiVersion = 'v1')
    {
        return static::$app['api.url']->version($apiVersion)->route($routeName);
    }
}
