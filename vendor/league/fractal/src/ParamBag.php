<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <me@philsturgeon.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Fractal;

/**
 * A handy interface for getting at include parameters.
 */
class ParamBag implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * Create a new parameter bag instance.
     *
     * @param array $params
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Get parameter values out of the bag.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->__get($key);
    }

    /**
     * Get parameter values out of the bag via the property access magic method.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    /**
     * Check if a param exists in the bag via an isset() check on the property.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->params[$key]);
    }

    /**
     * Disallow changing the value of params in the data bag via property access.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function __set($key, $value)
    {
        throw new \LogicException('Modifying parameters is not permitted');
    }

    /**
     * Disallow unsetting params in the data bag via property access.
     *
     * @param string $key
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function __unset($key)
    {
        throw new \LogicException('Modifying parameters is not permitted');
    }

    /**
     * Check if a param exists in the bag via an isset() and array access.
     *
     * @param string $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->__isset($key);
    }

    /**
     * Get parameter values out of the bag via array access.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->__get($key);
    }

    /**
     * Disallow changing the value of params in the data bag via array access.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        throw new \LogicException('Modifying parameters is not permitted');
    }

    /**
     * Disallow unsetting params in the data bag via array access.
     *
     * @param string $key
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        throw new \LogicException('Modifying parameters is not permitted');
    }

    /**
     * IteratorAggregate for iterating over the object like an array.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->params);
    }
}
