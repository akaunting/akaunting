<?php

namespace Dingo\Api\Event;

use Dingo\Api\Http\Request;
use Illuminate\Contracts\Container\Container;

class RequestWasMatched
{
    /**
     * Request instance.
     *
     * @var \Dingo\Api\Http\Request
     */
    public $request;

    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    public $app;

    /**
     * Create a new request was matched event.
     *
     * @param \Dingo\Api\Http\Request                   $request
     * @param \Illuminate\Contracts\Container\Container $app
     *
     * @return void
     */
    public function __construct(Request $request, Container $app)
    {
        $this->request = $request;
        $this->app = $app;
    }
}
