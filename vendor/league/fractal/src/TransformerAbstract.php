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

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;
use League\Fractal\Resource\Primitive;
use League\Fractal\Resource\ResourceInterface;

/**
 * Transformer Abstract
 *
 * All Transformer classes should extend this to utilize the convenience methods
 * collection() and item(), and make the self::$availableIncludes property available.
 * Extend it and add a `transform()` method to transform any default or included data
 * into a basic array.
 */
abstract class TransformerAbstract
{
    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Include resources without needing it to be requested.
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * The transformer should know about the current scope, so we can fetch relevant params.
     *
     * @var Scope
     */
    protected $currentScope;

    /**
     * Getter for availableIncludes.
     *
     * @return array
     */
    public function getAvailableIncludes()
    {
        return $this->availableIncludes;
    }

    /**
     * Getter for defaultIncludes.
     *
     * @return array
     */
    public function getDefaultIncludes()
    {
        return $this->defaultIncludes;
    }

    /**
     * Getter for currentScope.
     *
     * @return \League\Fractal\Scope
     */
    public function getCurrentScope()
    {
        return $this->currentScope;
    }

    /**
     * Figure out which includes we need.
     *
     * @internal
     *
     * @param Scope $scope
     *
     * @return array
     */
    private function figureOutWhichIncludes(Scope $scope)
    {
        $includes = $this->getDefaultIncludes();

        foreach ($this->getAvailableIncludes() as $include) {
            if ($scope->isRequested($include)) {
                $includes[] = $include;
            }
        }

        foreach ($includes as $include) {
            if ($scope->isExcluded($include)) {
                $includes = array_diff($includes, [$include]);
            }
        }

        return $includes;
    }

    /**
     * This method is fired to loop through available includes, see if any of
     * them are requested and permitted for this scope.
     *
     * @internal
     *
     * @param Scope $scope
     * @param mixed $data
     *
     * @return array
     */
    public function processIncludedResources(Scope $scope, $data)
    {
        $includedData = [];

        $includes = $this->figureOutWhichIncludes($scope);

        foreach ($includes as $include) {
            $includedData = $this->includeResourceIfAvailable(
                $scope,
                $data,
                $includedData,
                $include
            );
        }

        return $includedData === [] ? false : $includedData;
    }

    /**
     * Include a resource only if it is available on the method.
     *
     * @internal
     *
     * @param Scope  $scope
     * @param mixed  $data
     * @param array  $includedData
     * @param string $include
     *
     * @return array
     */
    private function includeResourceIfAvailable(
        Scope $scope,
        $data,
        $includedData,
        $include
    ) {
        if ($resource = $this->callIncludeMethod($scope, $include, $data)) {
            $childScope = $scope->embedChildScope($include, $resource);

            if ($childScope->getResource() instanceof Primitive) {
                $includedData[$include] = $childScope->transformPrimitiveResource();
            } else {
                $includedData[$include] = $childScope->toArray();
            }
        }

        return $includedData;
    }

    /**
     * Call Include Method.
     *
     * @internal
     *
     * @param Scope  $scope
     * @param string $includeName
     * @param mixed  $data
     *
     * @throws \Exception
     *
     * @return \League\Fractal\Resource\ResourceInterface
     */
    protected function callIncludeMethod(Scope $scope, $includeName, $data)
    {
        $scopeIdentifier = $scope->getIdentifier($includeName);
        $params = $scope->getManager()->getIncludeParams($scopeIdentifier);

        // Check if the method name actually exists
        $methodName = 'include'.str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('-', ' ', $includeName))));

        $resource = call_user_func([$this, $methodName], $data, $params);

        if ($resource === null) {
            return false;
        }

        if (! $resource instanceof ResourceInterface) {
            throw new \Exception(sprintf(
                'Invalid return value from %s::%s(). Expected %s, received %s.',
                __CLASS__,
                $methodName,
                'League\Fractal\Resource\ResourceInterface',
                is_object($resource) ? get_class($resource) : gettype($resource)
            ));
        }

        return $resource;
    }

    /**
     * Setter for availableIncludes.
     *
     * @param array $availableIncludes
     *
     * @return $this
     */
    public function setAvailableIncludes($availableIncludes)
    {
        $this->availableIncludes = $availableIncludes;

        return $this;
    }

    /**
     * Setter for defaultIncludes.
     *
     * @param array $defaultIncludes
     *
     * @return $this
     */
    public function setDefaultIncludes($defaultIncludes)
    {
        $this->defaultIncludes = $defaultIncludes;

        return $this;
    }

    /**
     * Setter for currentScope.
     *
     * @param Scope $currentScope
     *
     * @return $this
     */
    public function setCurrentScope($currentScope)
    {
        $this->currentScope = $currentScope;

        return $this;
    }

    /**
     * Create a new primitive resource object.
     *
     * @param mixed                        $data
     * @param callable|null                $transformer
     * @param string                       $resourceKey
     *
     * @return Primitive
     */
    protected function primitive($data, $transformer = null, $resourceKey = null)
    {
        return new Primitive($data, $transformer, $resourceKey);
    }

    /**
     * Create a new item resource object.
     *
     * @param mixed                        $data
     * @param TransformerAbstract|callable $transformer
     * @param string                       $resourceKey
     *
     * @return Item
     */
    protected function item($data, $transformer, $resourceKey = null)
    {
        return new Item($data, $transformer, $resourceKey);
    }

    /**
     * Create a new collection resource object.
     *
     * @param mixed                        $data
     * @param TransformerAbstract|callable $transformer
     * @param string                       $resourceKey
     *
     * @return Collection
     */
    protected function collection($data, $transformer, $resourceKey = null)
    {
        return new Collection($data, $transformer, $resourceKey);
    }

    /**
     * Create a new null resource object.
     *
     * @return NullResource
     */
    protected function null()
    {
        return new NullResource();
    }
}
