<?php

namespace Bugsnag\Request;

interface ResolverInterface
{
    /**
     * Resolve the current request.
     *
     * @return \Bugsnag\Request\RequestInterface
     */
    public function resolve();
}
