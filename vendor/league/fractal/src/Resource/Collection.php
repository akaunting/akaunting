<?php

/*
 * This file is part of the League\Fractal package.
 *
 * (c) Phil Sturgeon <me@philsturgeon.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Fractal\Resource;

use ArrayIterator;
use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;

/**
 * Resource Collection
 *
 * The data can be a collection of any sort of data, as long as the
 * "collection" is either array or an object implementing ArrayIterator.
 */
class Collection extends ResourceAbstract
{
    /**
     * A collection of data.
     *
     * @var array|ArrayIterator
     */
    protected $data;

    /**
     * The paginator instance.
     *
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * The cursor instance.
     *
     * @var CursorInterface
     */
    protected $cursor;

    /**
     * Get the paginator instance.
     *
     * @return PaginatorInterface
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * Determine if the resource has a paginator implementation.
     *
     * @return bool
     */
    public function hasPaginator()
    {
        return $this->paginator instanceof PaginatorInterface;
    }

    /**
     * Get the cursor instance.
     *
     * @return CursorInterface
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * Determine if the resource has a cursor implementation.
     *
     * @return bool
     */
    public function hasCursor()
    {
        return $this->cursor instanceof CursorInterface;
    }

    /**
     * Set the paginator instance.
     *
     * @param PaginatorInterface $paginator
     *
     * @return $this
     */
    public function setPaginator(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * Set the cursor instance.
     *
     * @param CursorInterface $cursor
     *
     * @return $this
     */
    public function setCursor(CursorInterface $cursor)
    {
        $this->cursor = $cursor;

        return $this;
    }
}
