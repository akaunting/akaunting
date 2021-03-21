<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <me@philsturgeon.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 **/

namespace League\Fractal;

use InvalidArgumentException;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Primitive;
use League\Fractal\Resource\NullResource;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\SerializerAbstract;

/**
 * Scope
 *
 * The scope class acts as a tracker, relating a specific resource in a specific
 * context. For example, the same resource could be attached to multiple scopes.
 * There are root scopes, parent scopes and child scopes.
 */
class Scope implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * @var string
     */
    protected $scopeIdentifier;

    /**
     * @var \League\Fractal\Manager
     */
    protected $manager;

    /**
     * @var ResourceInterface
     */
    protected $resource;

    /**
     * @var array
     */
    protected $parentScopes = [];

    /**
     * Create a new scope instance.
     *
     * @param Manager           $manager
     * @param ResourceInterface $resource
     * @param string            $scopeIdentifier
     *
     * @return void
     */
    public function __construct(Manager $manager, ResourceInterface $resource, $scopeIdentifier = null)
    {
        $this->manager = $manager;
        $this->resource = $resource;
        $this->scopeIdentifier = $scopeIdentifier;
    }

    /**
     * Embed a scope as a child of the current scope.
     *
     * @internal
     *
     * @param string            $scopeIdentifier
     * @param ResourceInterface $resource
     *
     * @return \League\Fractal\Scope
     */
    public function embedChildScope($scopeIdentifier, $resource)
    {
        return $this->manager->createData($resource, $scopeIdentifier, $this);
    }

    /**
     * Get the current identifier.
     *
     * @return string
     */
    public function getScopeIdentifier()
    {
        return $this->scopeIdentifier;
    }

    /**
     * Get the unique identifier for this scope.
     *
     * @param string $appendIdentifier
     *
     * @return string
     */
    public function getIdentifier($appendIdentifier = null)
    {
        $identifierParts = array_merge($this->parentScopes, [$this->scopeIdentifier, $appendIdentifier]);

        return implode('.', array_filter($identifierParts));
    }

    /**
     * Getter for parentScopes.
     *
     * @return mixed
     */
    public function getParentScopes()
    {
        return $this->parentScopes;
    }

    /**
     * Getter for resource.
     *
     * @return ResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Getter for manager.
     *
     * @return \League\Fractal\Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Is Requested.
     *
     * Check if - in relation to the current scope - this specific segment is allowed.
     * That means, if a.b.c is requested and the current scope is a.b, then c is allowed. If the current
     * scope is a then c is not allowed, even if it is there and potentially transformable.
     *
     * @internal
     *
     * @param string $checkScopeSegment
     *
     * @return bool Returns the new number of elements in the array.
     */
    public function isRequested($checkScopeSegment)
    {
        if ($this->parentScopes) {
            $scopeArray = array_slice($this->parentScopes, 1);
            array_push($scopeArray, $this->scopeIdentifier, $checkScopeSegment);
        } else {
            $scopeArray = [$checkScopeSegment];
        }

        $scopeString = implode('.', (array) $scopeArray);

        return in_array($scopeString, $this->manager->getRequestedIncludes());
    }

    /**
     * Is Excluded.
     *
     * Check if - in relation to the current scope - this specific segment should
     * be excluded. That means, if a.b.c is excluded and the current scope is a.b,
     * then c will not be allowed in the transformation whether it appears in
     * the list of default or available, requested includes.
     *
     * @internal
     *
     * @param string $checkScopeSegment
     *
     * @return bool
     */
    public function isExcluded($checkScopeSegment)
    {
        if ($this->parentScopes) {
            $scopeArray = array_slice($this->parentScopes, 1);
            array_push($scopeArray, $this->scopeIdentifier, $checkScopeSegment);
        } else {
            $scopeArray = [$checkScopeSegment];
        }

        $scopeString = implode('.', (array) $scopeArray);

        return in_array($scopeString, $this->manager->getRequestedExcludes());
    }

    /**
     * Push Parent Scope.
     *
     * Push a scope identifier into parentScopes
     *
     * @internal
     *
     * @param string $identifierSegment
     *
     * @return int Returns the new number of elements in the array.
     */
    public function pushParentScope($identifierSegment)
    {
        return array_push($this->parentScopes, $identifierSegment);
    }

    /**
     * Set parent scopes.
     *
     * @internal
     *
     * @param string[] $parentScopes Value to set.
     *
     * @return $this
     */
    public function setParentScopes($parentScopes)
    {
        $this->parentScopes = $parentScopes;

        return $this;
    }

    /**
     * Convert the current data for this scope to an array.
     *
     * @return array
     */
    public function toArray()
    {
        list($rawData, $rawIncludedData) = $this->executeResourceTransformers();

        $serializer = $this->manager->getSerializer();

        $data = $this->serializeResource($serializer, $rawData);

        // If the serializer wants the includes to be side-loaded then we'll
        // serialize the included data and merge it with the data.
        if ($serializer->sideloadIncludes()) {
            //Filter out any relation that wasn't requested
            $rawIncludedData = array_map(array($this, 'filterFieldsets'), $rawIncludedData);

            $includedData = $serializer->includedData($this->resource, $rawIncludedData);

            // If the serializer wants to inject additional information
            // about the included resources, it can do so now.
            $data = $serializer->injectData($data, $rawIncludedData);

            if ($this->isRootScope()) {
                // If the serializer wants to have a final word about all
                // the objects that are sideloaded, it can do so now.
                $includedData = $serializer->filterIncludes(
                    $includedData,
                    $data
                );
            }

            $data = $data + $includedData;
        }

        if (!empty($this->availableIncludes)) {
            $data = $serializer->injectAvailableIncludeData($data, $this->availableIncludes);
        }

        if ($this->resource instanceof Collection) {
            if ($this->resource->hasCursor()) {
                $pagination = $serializer->cursor($this->resource->getCursor());
            } elseif ($this->resource->hasPaginator()) {
                $pagination = $serializer->paginator($this->resource->getPaginator());
            }

            if (! empty($pagination)) {
                $this->resource->setMetaValue(key($pagination), current($pagination));
            }
        }

        // Pull out all of OUR metadata and any custom meta data to merge with the main level data
        $meta = $serializer->meta($this->resource->getMeta());

        // in case of returning NullResource we should return null and not to go with array_merge
        if (is_null($data)) {
            if (!empty($meta)) {
                return $meta;
            }
            return null;
        }

        return $data + $meta;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

  /**
     * Convert the current data for this scope to JSON.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this, $options);
    }

    /**
     * Transformer a primitive resource
     *
     * @return mixed
     */
    public function transformPrimitiveResource()
    {
        if (! ($this->resource instanceof Primitive)) {
            throw new InvalidArgumentException(
                'Argument $resource should be an instance of League\Fractal\Resource\Primitive'
            );
        }

        $transformer = $this->resource->getTransformer();
        $data = $this->resource->getData();

        if (null === $transformer) {
            $transformedData = $data;
        } elseif (is_callable($transformer)) {
            $transformedData = call_user_func($transformer, $data);
        } else {
            $transformer->setCurrentScope($this);
            $transformedData = $transformer->transform($data);
        }

        return $transformedData;
    }

    /**
     * Execute the resources transformer and return the data and included data.
     *
     * @internal
     *
     * @return array
     */
    protected function executeResourceTransformers()
    {
        $transformer = $this->resource->getTransformer();
        $data = $this->resource->getData();

        $transformedData = $includedData = [];

        if ($this->resource instanceof Item) {
            list($transformedData, $includedData[]) = $this->fireTransformer($transformer, $data);
        } elseif ($this->resource instanceof Collection) {
            foreach ($data as $value) {
                list($transformedData[], $includedData[]) = $this->fireTransformer($transformer, $value);
            }
        } elseif ($this->resource instanceof NullResource) {
            $transformedData = null;
            $includedData = [];
        } else {
            throw new InvalidArgumentException(
                'Argument $resource should be an instance of League\Fractal\Resource\Item'
                .' or League\Fractal\Resource\Collection'
            );
        }

        return [$transformedData, $includedData];
    }

    /**
     * Serialize a resource
     *
     * @internal
     *
     * @param SerializerAbstract $serializer
     * @param mixed              $data
     *
     * @return array
     */
    protected function serializeResource(SerializerAbstract $serializer, $data)
    {
        $resourceKey = $this->resource->getResourceKey();

        if ($this->resource instanceof Collection) {
            return $serializer->collection($resourceKey, $data);
        }

        if ($this->resource instanceof Item) {
            return $serializer->item($resourceKey, $data);
        }

        return $serializer->null();
    }

    /**
     * Fire the main transformer.
     *
     * @internal
     *
     * @param TransformerAbstract|callable $transformer
     * @param mixed                        $data
     *
     * @return array
     */
    protected function fireTransformer($transformer, $data)
    {
        $includedData = [];

        if (is_callable($transformer)) {
            $transformedData = call_user_func($transformer, $data);
        } else {
            $transformer->setCurrentScope($this);
            $transformedData = $transformer->transform($data);
        }

        if ($this->transformerHasIncludes($transformer)) {
            $includedData = $this->fireIncludedTransformers($transformer, $data);
            $transformedData = $this->manager->getSerializer()->mergeIncludes($transformedData, $includedData);
        }

        //Stick only with requested fields
        $transformedData = $this->filterFieldsets($transformedData);

        return [$transformedData, $includedData];
    }

    /**
     * Fire the included transformers.
     *
     * @internal
     *
     * @param \League\Fractal\TransformerAbstract $transformer
     * @param mixed                               $data
     *
     * @return array
     */
    protected function fireIncludedTransformers($transformer, $data)
    {
        $this->availableIncludes = $transformer->getAvailableIncludes();

        return $transformer->processIncludedResources($this, $data) ?: [];
    }

    /**
     * Determine if a transformer has any available includes.
     *
     * @internal
     *
     * @param TransformerAbstract|callable $transformer
     *
     * @return bool
     */
    protected function transformerHasIncludes($transformer)
    {
        if (! $transformer instanceof TransformerAbstract) {
            return false;
        }

        $defaultIncludes = $transformer->getDefaultIncludes();
        $availableIncludes = $transformer->getAvailableIncludes();

        return ! empty($defaultIncludes) || ! empty($availableIncludes);
    }

    /**
     * Check, if this is the root scope.
     *
     * @return bool
     */
    protected function isRootScope()
    {
        return empty($this->parentScopes);
    }

    /**
     * Filter the provided data with the requested filter fieldset for
     * the scope resource
     *
     * @internal
     *
     * @param array  $data
     *
     * @return array
     */
    protected function filterFieldsets(array $data)
    {
        if (!$this->hasFilterFieldset()) {
            return $data;
        }
        $serializer = $this->manager->getSerializer();
        $requestedFieldset = iterator_to_array($this->getFilterFieldset());
        //Build the array of requested fieldsets with the mandatory serializer fields
        $filterFieldset = array_flip(
            array_merge(
                $serializer->getMandatoryFields(),
                $requestedFieldset
            )
        );
        return array_intersect_key($data, $filterFieldset);
    }

    /**
     * Return the requested filter fieldset for the scope resource
     *
     * @internal
     *
     * @return \League\Fractal\ParamBag|null
     */
    protected function getFilterFieldset()
    {
        return $this->manager->getFieldset($this->getResourceType());
    }

    /**
     * Check if there are requested filter fieldsets for the scope resource.
     *
     * @internal
     *
     * @return bool
     */
    protected function hasFilterFieldset()
    {
        return $this->getFilterFieldset() !== null;
    }

    /**
     * Return the scope resource type.
     *
     * @internal
     *
     * @return string
     */
    protected function getResourceType()
    {
        return $this->resource->getResourceKey();
    }
}
