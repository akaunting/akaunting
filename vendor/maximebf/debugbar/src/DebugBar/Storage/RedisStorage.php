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

/**
 * Stores collected data into Redis
 */
class RedisStorage implements StorageInterface
{
    protected $redis;

    protected $hash;

    /**
     * @param  \Predis\Client $redis Redis Client
     * @param  string $hash
     */
    public function __construct($redis, $hash = 'phpdebugbar')
    {
        $this->redis = $redis;
        $this->hash = $hash;
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $data)
    {
        $this->redis->hset("$this->hash:meta", $id, serialize($data['__meta']));
        unset($data['__meta']);
        $this->redis->hset("$this->hash:data", $id, serialize($data));
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return array_merge(unserialize($this->redis->hget("$this->hash:data", $id)),
            array('__meta' => unserialize($this->redis->hget("$this->hash:meta", $id))));
    }

    /**
     * {@inheritdoc}
     */
    public function find(array $filters = array(), $max = 20, $offset = 0)
    {
        $results = array();
        $cursor = "0";
        do {
            list($cursor, $data) = $this->redis->hscan("$this->hash:meta", $cursor);

            foreach ($data as $meta) {
                if ($meta = unserialize($meta)) {
                    if ($this->filter($meta, $filters)) {
                        $results[] = $meta;
                    }
                }
            }
        } while($cursor);
        
        usort($results, function ($a, $b) {
            return $a['utime'] < $b['utime'];
        });
        
        return array_slice($results, $offset, $max);
    }

    /**
     * Filter the metadata for matches.
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
        $this->redis->del($this->hash);
    }
}
