<?php

namespace Http\Message;

/**
 * Cookie Jar holds a set of Cookies.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class CookieJar implements \Countable, \IteratorAggregate
{
    /**
     * @var \SplObjectStorage<Cookie, mixed>
     */
    private $cookies;

    public function __construct()
    {
        $this->cookies = new \SplObjectStorage();
    }

    /**
     * Checks if there is a cookie.
     *
     * @return bool
     */
    public function hasCookie(Cookie $cookie)
    {
        return $this->cookies->contains($cookie);
    }

    /**
     * Adds a cookie.
     */
    public function addCookie(Cookie $cookie)
    {
        if (!$this->hasCookie($cookie)) {
            $cookies = $this->getMatchingCookies($cookie);

            foreach ($cookies as $matchingCookie) {
                if ($cookie->getValue() !== $matchingCookie->getValue() || $cookie->getMaxAge() > $matchingCookie->getMaxAge()) {
                    $this->removeCookie($matchingCookie);

                    continue;
                }
            }

            if ($cookie->hasValue()) {
                $this->cookies->attach($cookie);
            }
        }
    }

    /**
     * Removes a cookie.
     */
    public function removeCookie(Cookie $cookie)
    {
        $this->cookies->detach($cookie);
    }

    /**
     * Returns the cookies.
     *
     * @return Cookie[]
     */
    public function getCookies()
    {
        $match = function ($matchCookie) {
            return true;
        };

        return $this->findMatchingCookies($match);
    }

    /**
     * Returns all matching cookies.
     *
     * @return Cookie[]
     */
    public function getMatchingCookies(Cookie $cookie)
    {
        $match = function ($matchCookie) use ($cookie) {
            return $matchCookie->match($cookie);
        };

        return $this->findMatchingCookies($match);
    }

    /**
     * Finds matching cookies based on a callable.
     *
     * @return Cookie[]
     */
    private function findMatchingCookies(callable $match)
    {
        $cookies = [];

        foreach ($this->cookies as $cookie) {
            if ($match($cookie)) {
                $cookies[] = $cookie;
            }
        }

        return $cookies;
    }

    /**
     * Checks if there are cookies.
     *
     * @return bool
     */
    public function hasCookies()
    {
        return $this->cookies->count() > 0;
    }

    /**
     * Sets the cookies and removes any previous one.
     *
     * @param Cookie[] $cookies
     */
    public function setCookies(array $cookies)
    {
        $this->clear();
        $this->addCookies($cookies);
    }

    /**
     * Adds some cookies.
     *
     * @param Cookie[] $cookies
     */
    public function addCookies(array $cookies)
    {
        foreach ($cookies as $cookie) {
            $this->addCookie($cookie);
        }
    }

    /**
     * Removes some cookies.
     *
     * @param Cookie[] $cookies
     */
    public function removeCookies(array $cookies)
    {
        foreach ($cookies as $cookie) {
            $this->removeCookie($cookie);
        }
    }

    /**
     * Removes cookies which match the given parameters.
     *
     * Null means that parameter should not be matched
     *
     * @param string|null $name
     * @param string|null $domain
     * @param string|null $path
     */
    public function removeMatchingCookies($name = null, $domain = null, $path = null)
    {
        $match = function ($cookie) use ($name, $domain, $path) {
            $match = true;

            if (isset($name)) {
                $match = $match && ($cookie->getName() === $name);
            }

            if (isset($domain)) {
                $match = $match && $cookie->matchDomain($domain);
            }

            if (isset($path)) {
                $match = $match && $cookie->matchPath($path);
            }

            return $match;
        };

        $cookies = $this->findMatchingCookies($match);

        $this->removeCookies($cookies);
    }

    /**
     * Removes all cookies.
     */
    public function clear()
    {
        $this->cookies = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return $this->cookies->count();
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return clone $this->cookies;
    }
}
