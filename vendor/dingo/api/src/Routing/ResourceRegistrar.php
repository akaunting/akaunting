<?php

namespace Dingo\Api\Routing;

use Illuminate\Support\Str;
use Illuminate\Routing\ResourceRegistrar as IlluminateResourceRegistrar;

class ResourceRegistrar extends IlluminateResourceRegistrar
{
    /**
     * The default actions for a resourceful controller. Excludes 'create' and 'edit'.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'store', 'show', 'update', 'destroy'];

    /**
     * Create a new resource registrar instance.
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
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     *
     * @return void
     */
    public function register($name, $controller, array $options = [])
    {
        if (isset($options['parameters']) && ! isset($this->parameters)) {
            $this->parameters = $options['parameters'];
        }

        // If the resource name contains a slash, we will assume the developer wishes to
        // register these resource routes with a prefix so we will set that up out of
        // the box so they don't have to mess with it. Otherwise, we will continue.
        if (Str::contains($name, '/')) {
            $this->prefixedResource($name, $controller, $options);

            return;
        }

        // We need to extract the base resource from the resource name. Nested resources
        // are supported in the framework, but we need to know what name to use for a
        // place-holder on the route parameters, which should be the base resources.
        $base = $this->getResourceWildcard(last(explode('.', $name)));

        $defaults = $this->resourceDefaults;

        foreach ($this->getResourceMethods($defaults, $options) as $m) {
            $this->{'addResource'.ucfirst($m)}($name, $base, $controller, $options);
        }
    }
}
