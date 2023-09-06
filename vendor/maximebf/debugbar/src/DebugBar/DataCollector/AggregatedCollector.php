<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\DataCollector;

use ArrayAccess;
use DebugBar\DebugBarException;

/**
 * Aggregates data from multiple collectors
 *
 * <code>
 * $aggcollector = new AggregateCollector('foobar');
 * $aggcollector->addCollector(new MessagesCollector('msg1'));
 * $aggcollector->addCollector(new MessagesCollector('msg2'));
 * $aggcollector['msg1']->addMessage('hello world');
 * </code>
 */
class AggregatedCollector implements DataCollectorInterface, ArrayAccess
{
    protected $name;

    protected $mergeProperty;

    protected $sort;

    protected $collectors = array();

    /**
     * @param string $name
     * @param string $mergeProperty
     * @param boolean $sort
     */
    public function __construct($name, $mergeProperty = null, $sort = false)
    {
        $this->name = $name;
        $this->mergeProperty = $mergeProperty;
        $this->sort = $sort;
    }

    /**
     * @param DataCollectorInterface $collector
     */
    public function addCollector(DataCollectorInterface $collector) : void
    {
        $this->collectors[$collector->getName()] = $collector;
    }

    /**
     * @return array
     */
    public function getCollectors() : array
    {
        return $this->collectors;
    }

    /**
     * Merge data from one of the key/value pair of the collected data
     *
     * @param string $property
     */
    public function setMergeProperty($property) : void
    {
        $this->mergeProperty = $property;
    }

    /**
     * @return string
     */
    public function getMergeProperty() : string
    {
        return $this->mergeProperty;
    }

    /**
     * Sorts the collected data
     *
     * If true, sorts using sort()
     * If it is a string, sorts the data using the value from a key/value pair of the array
     *
     * @param bool|string $sort
     */
    public function setSort($sort) : void
    {
        $this->sort = $sort;
    }

    /**
     * @return bool|string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return array
     */
    public function collect() : array
    {
        $aggregate = array();
        foreach ($this->collectors as $collector) {
            $data = $collector->collect();
            if ($this->mergeProperty !== null) {
                $data = $data[$this->mergeProperty];
            }
            $aggregate = array_merge($aggregate, $data);
        }

        return $this->sort($aggregate);
    }

    /**
     * Sorts the collected data
     *
     * @param array $data
     * @return array
     */
    protected function sort($data) : array
    {
        if (is_string($this->sort)) {
            $p = $this->sort;
            usort($data, function ($a, $b) use ($p) {
                if ($a[$p] == $b[$p]) {
                    return 0;
                }
                return $a[$p] < $b[$p] ? -1 : 1;
            });
        } elseif ($this->sort === true) {
            sort($data);
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    // --------------------------------------------
    // ArrayAccess implementation

    /**
     * @param mixed $key
     * @param mixed $value
     * @throws DebugBarException
     */
    public function offsetSet($key, $value): void
    {
        throw new DebugBarException("AggregatedCollector[] is read-only");
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        return $this->collectors[$key];
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return isset($this->collectors[$key]);
    }

    /**
     * @param mixed $key
     * @throws DebugBarException
     */
    public function offsetUnset($key): void
    {
        throw new DebugBarException("AggregatedCollector[] is read-only");
    }
}
