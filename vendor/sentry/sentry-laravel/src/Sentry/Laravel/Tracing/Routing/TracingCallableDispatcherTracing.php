<?php

namespace Sentry\Laravel\Tracing\Routing;

use Illuminate\Routing\Contracts\CallableDispatcher;
use Illuminate\Routing\Route;

class TracingCallableDispatcherTracing extends TracingRoutingDispatcher implements CallableDispatcher
{
    /** @var \Illuminate\Routing\Contracts\CallableDispatcher */
    private $dispatcher;

    public function __construct(CallableDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(Route $route, $callable)
    {
        return $this->wrapRouteDispatch(function () use ($route, $callable) {
            return $this->dispatcher->dispatch($route, $callable);
        }, $route);
    }
}
