<?php

namespace Monooso\Unobserve;

use Illuminate\Contracts\Container\Container;

class ProxyManager
{
    /** @var Container */
    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function register($target, array $events)
    {
        $proxy = $this->app->make(Proxy::class, ['target' => $target, 'events' => $events]);

        $this->app->instance(get_class($target), $proxy);
    }

    public function unregister($target)
    {
        $this->app->instance(get_class($target), $target);
    }
}
