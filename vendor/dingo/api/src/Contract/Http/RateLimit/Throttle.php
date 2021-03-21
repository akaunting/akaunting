<?php

namespace Dingo\Api\Contract\Http\RateLimit;

use Illuminate\Container\Container;

interface Throttle
{
    /**
     * Attempt to match the throttle against a given condition.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return bool
     */
    public function match(Container $container);

    /**
     * Get the time in minutes that the throttles request limit will expire.
     *
     * @return int
     */
    public function getExpires();

    /**
     * Get the throttles request limit.
     *
     * @return int
     */
    public function getLimit();
}
