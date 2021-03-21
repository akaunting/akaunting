<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Bridge;

use CacheCache\Cache;
use CacheCache\LoggingBackend;
use Monolog\Logger;

/**
 * Collects CacheCache operations
 *
 * http://maximebf.github.io/CacheCache/
 *
 * Example:
 * <code>
 * $debugbar->addCollector(new CacheCacheCollector(CacheManager::get('default')));
 * // or
 * $debugbar->addCollector(new CacheCacheCollector());
 * $debugbar['cache']->addCache(CacheManager::get('default'));
 * </code>
 */
class CacheCacheCollector extends MonologCollector
{
    protected $logger;

    /**
     * CacheCacheCollector constructor.
     * @param Cache|null $cache
     * @param Logger|null $logger
     * @param bool $level
     * @param bool $bubble
     */
    public function __construct(Cache $cache = null, Logger $logger = null, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct(null, $level, $bubble);

        if ($logger === null) {
            $logger = new Logger('Cache');
        }
        $this->logger = $logger;

        if ($cache !== null) {
            $this->addCache($cache);
        }
    }

    /**
     * @param Cache $cache
     */
    public function addCache(Cache $cache)
    {
        $backend = $cache->getBackend();
        if (!($backend instanceof LoggingBackend)) {
            $backend = new LoggingBackend($backend, $this->logger);
        }
        $cache->setBackend($backend);
        $this->addLogger($backend->getLogger());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cache';
    }
}
