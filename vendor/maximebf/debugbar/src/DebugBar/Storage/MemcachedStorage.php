<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Storage;

use Memcached;
use ReflectionMethod;

/**
 * Stores collected data into Memcache using the Memcached extension
 */
class MemcachedStorage implements StorageInterface
{
    protected $memcached;

    protected $keyNamespace;

    protected $expiration;

    protected $newGetMultiSignature;

    /**
     * @param Memcached $memcached
     * @param string $keyNamespace Namespace for Memcached key names (to avoid conflict with other Memcached users).
     * @param int $expiration Expiration for Memcached entries (see Expiration Times in Memcached documentation).
     */
    public function __construct(Memcached $memcached, $keyNamespace = 'phpdebugbar', $expiration = 0)
    {
        $this->memcached = $memcached;
        $this->keyNamespace = $keyNamespace;
        $this->expiration = $expiration;
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $data)
    {
        $key = $this->createKey($id);
        $this->memcached->set($key, $data, $this->expiration);
        if (!$this->memcached->append($this->keyNamespace, "|$key")) {
            $this->memcached->set($this->keyNamespace, $key, $this->expiration);
        } else if ($this->expiration) {
            // append doesn't support updating expiration, so do it here:
            $this->memcached->touch($this->keyNamespace, $this->expiration);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->memcached->get($this->createKey($id));
    }

    /**
     * {@inheritdoc}
     */
    public function find(array $filters = array(), $max = 20, $offset = 0)
    {
        if (!($keys = $this->memcached->get($this->keyNamespace))) {
            return array();
        }

        $results = array();
        $keys = array_reverse(explode('|', $keys)); // Reverse so newest comes first
        $keyPosition = 0; // Index in $keys to try to get next items from
        $remainingItems = $max + $offset; // Try to obtain this many remaining items
        // Loop until we've found $remainingItems matching items or no more items may exist.
        while ($remainingItems > 0 && $keyPosition < count($keys)) {
            // Consume some keys from $keys:
            $itemsToGet = array_slice($keys, $keyPosition, $remainingItems);
            $keyPosition += $remainingItems;
            // Try to get them, and filter them:
            $newItems = $this->memcachedGetMulti($itemsToGet, Memcached::GET_PRESERVE_ORDER);
            if ($newItems) {
                foreach ($newItems as $data) {
                    $meta = $data['__meta'];
                    if ($this->filter($meta, $filters)) {
                        $remainingItems--;
                        // Keep the result only if we've discarded $offset items first
                        if ($offset <= 0) {
                            $results[] = $meta;
                        } else {
                            $offset--;
                        }
                    }
                }
            }
        }
        return $results;
    }

    /**
     * Filter the metadata for matches.
     * 
     * @param  array $meta
     * @param  array $filters
     * @return bool
     */
    protected function filter($meta, $filters)
    {
        foreach ($filters as $key => $value) {
            if (!isset($meta[$key]) || fnmatch($value, $meta[$key]) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        if (!($keys = $this->memcached->get($this->keyNamespace))) {
            return;
        }
        $this->memcached->delete($this->keyNamespace);
        $this->memcached->deleteMulti(explode('|', $keys));
    }

    /**
     * @param  string $id
     * @return string 
     */
    protected function createKey($id)
    {
        return md5("{$this->keyNamespace}.$id");
    }

    /**
     * The memcached getMulti function changed in version 3.0.0 to only have two parameters.
     *
     * @param array $keys
     * @param int $flags
     */
    protected function memcachedGetMulti($keys, $flags)
    {
        if ($this->newGetMultiSignature === null) {
            $this->newGetMultiSignature = (new ReflectionMethod('Memcached', 'getMulti'))->getNumberOfParameters() === 2;
        }
        if ($this->newGetMultiSignature) {
            return $this->memcached->getMulti($keys, $flags);
        } else {
            $null = null;
            return $this->memcached->getMulti($keys, $null, $flags);
        }
    }
}
