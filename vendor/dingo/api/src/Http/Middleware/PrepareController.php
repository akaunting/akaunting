<?php

namespace Dingo\Api\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Router;

class PrepareController
{
    /**
     * Dingo router instance.
     *
     * @var \Dingo\Api\Routing\Router
     */
    protected $router;

    /**
     * Create a new prepare controller instance.
     *
     * @param \Dingo\Api\Routing\Router $router
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Handle the request.
     *
     * @param \Dingo\Api\Http\Request $request
     * @param \Closure                $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // To prepare the controller all we need to do is call the current method on the router to fetch
        // the current route. This will create a new Dingo\Api\Routing\Route instance and prepare the
        // controller by binding it as a singleton in the container. This will result in the
        // controller only be instantiated once per request.
        $this->router->current();

        return $next($request);
    }
}
