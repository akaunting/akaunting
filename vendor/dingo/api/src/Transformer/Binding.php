<?php

namespace Dingo\Api\Transformer;

use Closure;
use RuntimeException;
use Illuminate\Container\Container;

class Binding
{
    /**
     * Illuminate container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Binding resolver.
     *
     * @var mixed
     */
    protected $resolver;

    /**
     * Array of parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Callback fired during transformation.
     *
     * @var \Closure
     */
    protected $callback;

    /**
     * Array of meta data.
     *
     * @var array
     */
    protected $meta = [];

    /**
     * Create a new binding instance.
     *
     * @param \Illuminate\Container\Container $container
     * @param mixed                           $resolver
     * @param array                           $parameters
     * @param \Closure                        $callback
     *
     * @return void
     */
    public function __construct(Container $container, $resolver, array $parameters = [], Closure $callback = null)
    {
        $this->container = $container;
        $this->resolver = $resolver;
        $this->parameters = $parameters;
        $this->callback = $callback;
    }

    /**
     * Resolve a transformer binding instance.
     *
     * @throws \RuntimeException
     *
     * @return object
     */
    public function resolveTransformer()
    {
        if (is_string($this->resolver)) {
            return $this->container->make($this->resolver);
        } elseif (is_callable($this->resolver)) {
            return call_user_func($this->resolver, $this->container);
        } elseif (is_object($this->resolver)) {
            return $this->resolver;
        }

        throw new RuntimeException('Unable to resolve transformer binding.');
    }

    /**
     * Fire the binding callback.
     *
     * @param string|array $parameters
     *
     * @return void
     */
    public function fireCallback($parameters = null)
    {
        if (is_callable($this->callback)) {
            call_user_func_array($this->callback, func_get_args());
        }
    }

    /**
     * Get the binding parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set the meta data for the binding.
     *
     * @param array $meta
     *
     * @return void
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * Add a meta data key/value pair.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function addMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }

    /**
     * Get the binding meta data.
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
