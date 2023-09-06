<?php

namespace Sentry\Laravel\Tracing\Routing;

use Illuminate\Routing\Contracts\ControllerDispatcher;
use Illuminate\Routing\Route;

class TracingControllerDispatcherTracing extends TracingRoutingDispatcher implements ControllerDispatcher
{
    /** @var \Illuminate\Routing\Contracts\ControllerDispatcher */
    private $dispatcher;

    public function __construct(ControllerDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(Route $route, $controller, $method)
    {
        return $this->wrapRouteDispatch(function () use ($route, $controller, $method) {
            return $this->dispatcher->dispatch($route, $controller, $method);
        }, $route);
    }

    public function getMiddleware($controller, $method)
    {
        return $this->dispatcher->getMiddleware($controller, $method);
    }
}
