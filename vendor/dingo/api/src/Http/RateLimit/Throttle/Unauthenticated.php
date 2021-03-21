<?php

namespace Dingo\Api\Http\RateLimit\Throttle;

use Illuminate\Container\Container;

class Unauthenticated extends Throttle
{
    /**
     * Unauthenticated throttle will be matched when request is not authenticated.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return bool
     */
    public function match(Container $container)
    {
        return ! $container['api.auth']->check();
    }
}
