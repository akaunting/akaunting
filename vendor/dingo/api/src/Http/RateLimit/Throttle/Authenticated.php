<?php

namespace Dingo\Api\Http\RateLimit\Throttle;

use Illuminate\Container\Container;

class Authenticated extends Throttle
{
    /**
     * Authenticated throttle will be matched when request is authenticated.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return bool
     */
    public function match(Container $container)
    {
        return $container['api.auth']->check();
    }
}
