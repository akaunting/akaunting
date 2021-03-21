<?php

namespace Http\Discovery;

use Http\Discovery\Exception\ClassInstantiationFailedException;
use Http\Discovery\Exception\DiscoveryFailedException;
use Http\Discovery\Exception\NoCandidateFoundException;
use Http\Discovery\Exception\StrategyUnavailableException;

/**
 * Registry that based find results on class existence.
 *
 * @author David de Boer <david@ddeboer.nl>
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class ClassDiscovery
{
    /**
     * A list of strategies to find classes.
     *
     * @var array
     */
    private static $strategies = [
        Strategy\PuliBetaStrategy::class,
        Strategy\CommonClassesStrategy::class,
        Strategy\CommonPsr17ClassesStrategy::class,
    ];

    /**
     * Discovery cache to make the second time we use discovery faster.
     *
     * @var array
     */
    private static $cache = [];

    /**
     * Finds a class.
     *
     * @param string $type
     *
     * @return string|\Closure
     *
     * @throws DiscoveryFailedException
     */
    protected static function findOneByType($type)
    {
        // Look in the cache
        if (null !== ($class = self::getFromCache($type))) {
            return $class;
        }

        $exceptions = [];
        foreach (self::$strategies as $strategy) {
            try {
                $candidates = call_user_func($strategy.'::getCandidates', $type);
            } catch (StrategyUnavailableException $e) {
                $exceptions[] = $e;

                continue;
            }

            foreach ($candidates as $candidate) {
                if (isset($candidate['condition'])) {
                    if (!self::evaluateCondition($candidate['condition'])) {
                        continue;
                    }
                }

                // save the result for later use
                self::storeInCache($type, $candidate);

                return $candidate['class'];
            }

            $exceptions[] = new NoCandidateFoundException($strategy, $candidates);
        }

        throw DiscoveryFailedException::create($exceptions);
    }

    /**
     * Get a value from cache.
     *
     * @param string $type
     *
     * @return string|null
     */
    private static function getFromCache($type)
    {
        if (!isset(self::$cache[$type])) {
            return;
        }

        $candidate = self::$cache[$type];
        if (isset($candidate['condition'])) {
            if (!self::evaluateCondition($candidate['condition'])) {
                return;
            }
        }

        return $candidate['class'];
    }

    /**
     * Store a value in cache.
     *
     * @param string $type
     * @param string $class
     */
    private static function storeInCache($type, $class)
    {
        self::$cache[$type] = $class;
    }

    /**
     * Set new strategies and clear the cache.
     *
     * @param array $strategies string array of fully qualified class name to a DiscoveryStrategy
     */
    public static function setStrategies(array $strategies)
    {
        self::$strategies = $strategies;
        self::clearCache();
    }

    /**
     * Returns the currently configured discovery strategies as fully qualified class names.
     *
     * @return string[]
     */
    public static function getStrategies(): iterable
    {
        return self::$strategies;
    }

    /**
     * Append a strategy at the end of the strategy queue.
     *
     * @param string $strategy Fully qualified class name to a DiscoveryStrategy
     */
    public static function appendStrategy($strategy)
    {
        self::$strategies[] = $strategy;
        self::clearCache();
    }

    /**
     * Prepend a strategy at the beginning of the strategy queue.
     *
     * @param string $strategy Fully qualified class name to a DiscoveryStrategy
     */
    public static function prependStrategy($strategy)
    {
        array_unshift(self::$strategies, $strategy);
        self::clearCache();
    }

    /**
     * Clear the cache.
     */
    public static function clearCache()
    {
        self::$cache = [];
    }

    /**
     * Evaluates conditions to boolean.
     *
     * @param mixed $condition
     *
     * @return bool
     */
    protected static function evaluateCondition($condition)
    {
        if (is_string($condition)) {
            // Should be extended for functions, extensions???
            return self::safeClassExists($condition);
        }
        if (is_callable($condition)) {
            return (bool) $condition();
        }
        if (is_bool($condition)) {
            return $condition;
        }
        if (is_array($condition)) {
            foreach ($condition as $c) {
                if (false === static::evaluateCondition($c)) {
                    // Immediately stop execution if the condition is false
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Get an instance of the $class.
     *
     * @param string|\Closure $class A FQCN of a class or a closure that instantiate the class.
     *
     * @return object
     *
     * @throws ClassInstantiationFailedException
     */
    protected static function instantiateClass($class)
    {
        try {
            if (is_string($class)) {
                return new $class();
            }

            if (is_callable($class)) {
                return $class();
            }
        } catch (\Exception $e) {
            throw new ClassInstantiationFailedException('Unexpected exception when instantiating class.', 0, $e);
        }

        throw new ClassInstantiationFailedException('Could not instantiate class because parameter is neither a callable nor a string');
    }

    /**
     * We want to do a "safe" version of PHP's "class_exists" because Magento has a bug
     * (or they call it a "feature"). Magento is throwing an exception if you do class_exists()
     * on a class that ends with "Factory" and if that file does not exits.
     *
     * This function will catch all potential exceptions and make sure it returns a boolean.
     *
     * @param string $class
     * @param bool   $autoload
     *
     * @return bool
     */
    public static function safeClassExists($class)
    {
        try {
            return class_exists($class) || interface_exists($class);
        } catch (\Exception $e) {
            return false;
        }
    }
}
