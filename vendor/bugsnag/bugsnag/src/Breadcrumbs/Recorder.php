<?php

namespace Bugsnag\Breadcrumbs;

use Countable;
use Iterator;

/**
 * @implements Iterator<int, Breadcrumb>
 */
class Recorder implements Countable, Iterator
{
    /**
     * The maximum number of breadcrumbs to store.
     *
     * @var int
     */
    private $maxBreadcrumbs = 50;

    /**
     * The recorded breadcrumbs.
     *
     * @var \Bugsnag\Breadcrumbs\Breadcrumb[]
     */
    private $breadcrumbs = [];

    /**
     * The iteration position.
     *
     * @var int
     */
    private $position = 0;

    /**
     * Record a breadcrumb.
     *
     * @param \Bugsnag\Breadcrumbs\Breadcrumb $breadcrumb
     *
     * @return void
     */
    public function record(Breadcrumb $breadcrumb)
    {
        $this->breadcrumbs[] = $breadcrumb;

        // drop the oldest breadcrumb if we're over the max
        if ($this->count() > $this->maxBreadcrumbs) {
            array_shift($this->breadcrumbs);
        }
    }

    /**
     * Clear all recorded breadcrumbs.
     *
     * @return void
     */
    public function clear()
    {
        $this->position = 0;
        $this->breadcrumbs = [];
    }

    /**
     * Set the maximum number of breadcrumbs that are allowed to be stored.
     *
     * This must be an integer between 0 and 100 (inclusive).
     *
     * @param int $maxBreadcrumbs
     *
     * @return void
     */
    public function setMaxBreadcrumbs($maxBreadcrumbs)
    {
        if (!is_int($maxBreadcrumbs) || $maxBreadcrumbs < 0 || $maxBreadcrumbs > 100) {
            error_log(
                'Bugsnag Warning: maxBreadcrumbs should be an integer between 0 and 100 (inclusive)'
            );

            return;
        }

        $this->maxBreadcrumbs = $maxBreadcrumbs;

        // drop the oldest breadcrumbs if we're over the max
        if ($this->count() > $this->maxBreadcrumbs) {
            $this->breadcrumbs = array_slice(
                $this->breadcrumbs,
                $this->count() - $this->maxBreadcrumbs
            );
        }
    }

    /**
     * Get the maximum number of breadcrumbs that are allowed to be stored.
     *
     * @return int
     */
    public function getMaxBreadcrumbs()
    {
        return $this->maxBreadcrumbs;
    }

    /**
     * Get the number of stored breadcrumbs.
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->breadcrumbs);
    }

    /**
     * Get the current item.
     *
     * @return \Bugsnag\Breadcrumbs\Breadcrumb
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->breadcrumbs[$this->position];
    }

    /**
     * Get the current key.
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->position;
    }

    /**
     * Advance the key position.
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        $this->position++;
    }

    /**
     * Rewind the key position.
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Is the current key position set?
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        return $this->position < $this->count();
    }
}
