<?php
namespace League\Fractal\Serializer;

use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Resource\ResourceInterface;

interface Serializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data);

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function item($resourceKey, array $data);

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null();

    /**
     * Serialize the included data.
     *
     * @param ResourceInterface $resource
     * @param array $data
     *
     * @return array
     */
    public function includedData(ResourceInterface $resource, array $data);

    /**
     * Serialize the meta.
     *
     * @param array $meta
     *
     * @return array
     */
    public function meta(array $meta);

    /**
     * Serialize the paginator.
     *
     * @param PaginatorInterface $paginator
     *
     * @return array
     */
    public function paginator(PaginatorInterface $paginator);

    /**
     * Serialize the cursor.
     *
     * @param CursorInterface $cursor
     *
     * @return array
     */
    public function cursor(CursorInterface $cursor);

    public function mergeIncludes($transformedData, $includedData);

    /**
     * Indicates if includes should be side-loaded.
     *
     * @return bool
     */
    public function sideloadIncludes();

    /**
     * Hook for the serializer to inject custom data based on the relationships of the resource.
     *
     * @param array $data
     * @param array $rawIncludedData
     *
     * @return array
     */
    public function injectData($data, $rawIncludedData);

    /**
     * Hook for the serializer to modify the final list of includes.
     *
     * @param array $includedData
     * @param array $data
     *
     * @return array
     */
    public function filterIncludes($includedData, $data);
}
