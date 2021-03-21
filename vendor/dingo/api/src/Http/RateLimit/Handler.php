<?php

namespace Dingo\Api\Http\RateLimit;

use Dingo\Api\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Dingo\Api\Http\RateLimit\Throttle\Route;
use Dingo\Api\Contract\Http\RateLimit\Throttle;
use Dingo\Api\Contract\Http\RateLimit\HasRateLimiter;

class Handler
{
    /**
     * Container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Cache instance.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Registered throttles.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $throttles;

    /**
     * Throttle used for rate limiting.
     *
     * @var \Dingo\Api\Contract\Http\RateLimit\Throttle
     */
    protected $throttle;

    /**
     * Request instance being throttled.
     *
     * @var \Dingo\Api\Http\Request
     */
    protected $request;

    /**
     * The key prefix used when throttling route specific requests.
     *
     * @var string
     */
    protected $keyPrefix;

    /**
     * A callback used to define the limiter.
     *
     * @var callable
     */
    protected $limiter;

    /**
     * Create a new rate limit handler instance.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Cache\CacheManager  $cache
     * @param array                           $throttles
     *
     * @return void
     */
    public function __construct(Container $container, CacheManager $cache, array $throttles)
    {
        $this->cache = $cache;
        $this->container = $container;
        $this->throttles = new Collection($throttles);
    }

    /**
     * Execute the rate limiting for the given request.
     *
     * @param \Dingo\Api\Http\Request $request
     * @param int                     $limit
     * @param int                     $expires
     *
     * @return void
     */
    public function rateLimitRequest(Request $request, $limit = 0, $expires = 0)
    {
        $this->request = $request;

        // If the throttle instance is already set then we'll just carry on as
        // per usual.
        if ($this->throttle instanceof Throttle) {

            // If the developer specified a certain amount of requests or expiration
        // time on a specific route then we'll always use the route specific
        // throttle with the given values.
        } elseif ($limit > 0 || $expires > 0) {
            $this->throttle = new Route(['limit' => $limit, 'expires' => $expires]);
            $this->keyPrefix = sha1($request->path());

        // Otherwise we'll use the throttle that gives the consumer the largest
        // amount of requests. If no matching throttle is found then rate
        // limiting will not be imposed for the request.
        } else {
            $this->throttle = $this->getMatchingThrottles()->sort(function ($a, $b) {
                return $a->getLimit() < $b->getLimit();
            })->first();
        }

        if (is_null($this->throttle)) {
            return;
        }

        if ($this->throttle instanceof HasRateLimiter) {
            $this->setRateLimiter([$this->throttle, 'getRateLimiter']);
        }

        $this->prepareCacheStore();

        $this->cache('requests', 0, $this->throttle->getExpires());
        $this->cache('expires', $this->throttle->getExpires(), $this->throttle->getExpires());
        $this->cache('reset', time() + ($this->throttle->getExpires() * 60), $this->throttle->getExpires());
        $this->increment('requests');
    }

    /**
     * Prepare the cache store.
     *
     * @return void
     */
    protected function prepareCacheStore()
    {
        if ($this->retrieve('expires') != $this->throttle->getExpires()) {
            $this->forget('requests');
            $this->forget('expires');
            $this->forget('reset');
        }
    }

    /**
     * Determine if the rate limit has been exceeded.
     *
     * @return bool
     */
    public function exceededRateLimit()
    {
        return $this->requestWasRateLimited() ? $this->retrieve('requests') > $this->throttle->getLimit() : false;
    }

    /**
     * Get matching throttles after executing the condition of each throttle.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getMatchingThrottles()
    {
        return $this->throttles->filter(function ($throttle) {
            return $throttle->match($this->container);
        });
    }

    /**
     * Namespace a cache key.
     *
     * @param string $key
     *
     * @return string
     */
    protected function key($key)
    {
        return sprintf('dingo.api.%s.%s', $key, $this->getRateLimiter());
    }

    /**
     * Cache a value under a given key for a certain amount of minutes.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     *
     * @return void
     */
    protected function cache($key, $value, $minutes)
    {
        $this->cache->add($this->key($key), $value, Carbon::now()->addMinutes($minutes));
    }

    /**
     * Retrieve a value from the cache store.
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function retrieve($key)
    {
        return $this->cache->get($this->key($key));
    }

    /**
     * Increment a key in the cache.
     *
     * @param string $key
     *
     * @return void
     */
    protected function increment($key)
    {
        $this->cache->increment($this->key($key));
    }

    /**
     * Forget a key in the cache.
     *
     * @param string $key
     *
     * @return void
     */
    protected function forget($key)
    {
        $this->cache->forget($this->key($key));
    }

    /**
     * Determine if the request was rate limited.
     *
     * @return bool
     */
    public function requestWasRateLimited()
    {
        return ! is_null($this->throttle);
    }

    /**
     * Get the rate limiter.
     *
     * @return string
     */
    public function getRateLimiter()
    {
        return call_user_func($this->limiter ?: function ($container, $request) {
            return $request->getClientIp();
        }, $this->container, $this->request);
    }

    /**
     * Set the rate limiter.
     *
     * @param callable $limiter
     *
     * @return void
     */
    public function setRateLimiter(callable $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Set the throttle to use for rate limiting.
     *
     * @param string|\Dingo\Api\Contract\Http\RateLimit\Throttle $throttle
     *
     * @return void
     */
    public function setThrottle($throttle)
    {
        if (is_string($throttle)) {
            $throttle = $this->container->make($throttle);
        }

        $this->throttle = $throttle;
    }

    /**
     * Get the throttle used to rate limit the request.
     *
     * @return \Dingo\Api\Contract\Http\RateLimit\Throttle
     */
    public function getThrottle()
    {
        return $this->throttle;
    }

    /**
     * Get the limit of the throttle used.
     *
     * @return int
     */
    public function getThrottleLimit()
    {
        return $this->throttle->getLimit();
    }

    /**
     * Get the remaining limit before the consumer is rate limited.
     *
     * @return int
     */
    public function getRemainingLimit()
    {
        $remaining = $this->throttle->getLimit() - $this->retrieve('requests');

        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Get the timestamp for when the current rate limiting will expire.
     *
     * @return int
     */
    public function getRateLimitReset()
    {
        return $this->retrieve('reset');
    }

    /**
     * Extend the rate limiter by adding a new throttle.
     *
     * @param callable|\Dingo\Api\Http\RateLimit\Throttle $throttle
     *
     * @return void
     */
    public function extend($throttle)
    {
        if (is_callable($throttle)) {
            $throttle = call_user_func($throttle, $this->container);
        }

        $this->throttles->push($throttle);
    }
}
