<?php

namespace Http\Message;

/**
 * Cookie Value Object.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @see http://tools.ietf.org/search/rfc6265
 */
final class Cookie
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $value;

    /**
     * @var int|null
     */
    private $maxAge;

    /**
     * @var string|null
     */
    private $domain;

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $secure;

    /**
     * @var bool
     */
    private $httpOnly;

    /**
     * Expires attribute is HTTP 1.0 only and should be avoided.
     *
     * @var \DateTime|null
     */
    private $expires;

    /**
     * @param string         $name
     * @param string|null    $value
     * @param int|null       $maxAge
     * @param string|null    $domain
     * @param string|null    $path
     * @param bool           $secure
     * @param bool           $httpOnly
     * @param \DateTime|null $expires  Expires attribute is HTTP 1.0 only and should be avoided.
     *
     * @throws \InvalidArgumentException if name, value or max age is not valid
     */
    public function __construct(
        $name,
        $value = null,
        $maxAge = null,
        $domain = null,
        $path = null,
        $secure = false,
        $httpOnly = false,
        \DateTime $expires = null
    ) {
        $this->validateName($name);
        $this->validateValue($value);
        $this->validateMaxAge($maxAge);

        $this->name = $name;
        $this->value = $value;
        $this->maxAge = $maxAge;
        $this->expires = $expires;
        $this->domain = $this->normalizeDomain($domain);
        $this->path = $this->normalizePath($path);
        $this->secure = (bool) $secure;
        $this->httpOnly = (bool) $httpOnly;
    }

    /**
     * Creates a new cookie without any attribute validation.
     *
     * @param string         $name
     * @param string|null    $value
     * @param int            $maxAge
     * @param string|null    $domain
     * @param string|null    $path
     * @param bool           $secure
     * @param bool           $httpOnly
     * @param \DateTime|null $expires  Expires attribute is HTTP 1.0 only and should be avoided.
     */
    public static function createWithoutValidation(
        $name,
        $value = null,
        $maxAge = null,
        $domain = null,
        $path = null,
        $secure = false,
        $httpOnly = false,
        \DateTime $expires = null
    ) {
        $cookie = new self('name', null, null, $domain, $path, $secure, $httpOnly, $expires);
        $cookie->name = $name;
        $cookie->value = $value;
        $cookie->maxAge = $maxAge;

        return $cookie;
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value.
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Checks if there is a value.
     *
     * @return bool
     */
    public function hasValue()
    {
        return isset($this->value);
    }

    /**
     * Sets the value.
     *
     * @param string|null $value
     *
     * @return Cookie
     */
    public function withValue($value)
    {
        $this->validateValue($value);

        $new = clone $this;
        $new->value = $value;

        return $new;
    }

    /**
     * Returns the max age.
     *
     * @return int|null
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * Checks if there is a max age.
     *
     * @return bool
     */
    public function hasMaxAge()
    {
        return isset($this->maxAge);
    }

    /**
     * Sets the max age.
     *
     * @param int|null $maxAge
     *
     * @return Cookie
     */
    public function withMaxAge($maxAge)
    {
        $this->validateMaxAge($maxAge);

        $new = clone $this;
        $new->maxAge = $maxAge;

        return $new;
    }

    /**
     * Returns the expiration time.
     *
     * @return \DateTime|null
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Checks if there is an expiration time.
     *
     * @return bool
     */
    public function hasExpires()
    {
        return isset($this->expires);
    }

    /**
     * Sets the expires.
     *
     * @return Cookie
     */
    public function withExpires(\DateTime $expires = null)
    {
        $new = clone $this;
        $new->expires = $expires;

        return $new;
    }

    /**
     * Checks if the cookie is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return isset($this->expires) and $this->expires < new \DateTime();
    }

    /**
     * Returns the domain.
     *
     * @return string|null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Checks if there is a domain.
     *
     * @return bool
     */
    public function hasDomain()
    {
        return isset($this->domain);
    }

    /**
     * Sets the domain.
     *
     * @param string|null $domain
     *
     * @return Cookie
     */
    public function withDomain($domain)
    {
        $new = clone $this;
        $new->domain = $this->normalizeDomain($domain);

        return $new;
    }

    /**
     * Checks whether this cookie is meant for this domain.
     *
     * @see http://tools.ietf.org/html/rfc6265#section-5.1.3
     *
     * @param string $domain
     *
     * @return bool
     */
    public function matchDomain($domain)
    {
        // Domain is not set or exact match
        if (!$this->hasDomain() || 0 === strcasecmp($domain, $this->domain)) {
            return true;
        }

        // Domain is not an IP address
        if (filter_var($domain, FILTER_VALIDATE_IP)) {
            return false;
        }

        return (bool) preg_match(sprintf('/\b%s$/i', preg_quote($this->domain)), $domain);
    }

    /**
     * Returns the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the path.
     *
     * @param string|null $path
     *
     * @return Cookie
     */
    public function withPath($path)
    {
        $new = clone $this;
        $new->path = $this->normalizePath($path);

        return $new;
    }

    /**
     * Checks whether this cookie is meant for this path.
     *
     * @see http://tools.ietf.org/html/rfc6265#section-5.1.4
     *
     * @param string $path
     *
     * @return bool
     */
    public function matchPath($path)
    {
        return $this->path === $path || (0 === strpos($path, rtrim($this->path, '/').'/'));
    }

    /**
     * Checks whether this cookie may only be sent over HTTPS.
     *
     * @return bool
     */
    public function isSecure()
    {
        return $this->secure;
    }

    /**
     * Sets whether this cookie should only be sent over HTTPS.
     *
     * @param bool $secure
     *
     * @return Cookie
     */
    public function withSecure($secure)
    {
        $new = clone $this;
        $new->secure = (bool) $secure;

        return $new;
    }

    /**
     * Check whether this cookie may not be accessed through Javascript.
     *
     * @return bool
     */
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
     * Sets whether this cookie may not be accessed through Javascript.
     *
     * @param bool $httpOnly
     *
     * @return Cookie
     */
    public function withHttpOnly($httpOnly)
    {
        $new = clone $this;
        $new->httpOnly = (bool) $httpOnly;

        return $new;
    }

    /**
     * Checks if this cookie represents the same cookie as $cookie.
     *
     * This does not compare the values, only name, domain and path.
     *
     * @param Cookie $cookie
     *
     * @return bool
     */
    public function match(self $cookie)
    {
        return $this->name === $cookie->name && $this->domain === $cookie->domain and $this->path === $cookie->path;
    }

    /**
     * Validates cookie attributes.
     *
     * @return bool
     */
    public function isValid()
    {
        try {
            $this->validateName($this->name);
            $this->validateValue($this->value);
            $this->validateMaxAge($this->maxAge);
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * Validates the name attribute.
     *
     * @see http://tools.ietf.org/search/rfc2616#section-2.2
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException if the name is empty or contains invalid characters
     */
    private function validateName($name)
    {
        if (strlen($name) < 1) {
            throw new \InvalidArgumentException('The name cannot be empty');
        }

        // Name attribute is a token as per spec in RFC 2616
        if (preg_match('/[\x00-\x20\x22\x28-\x29\x2C\x2F\x3A-\x40\x5B-\x5D\x7B\x7D\x7F]/', $name)) {
            throw new \InvalidArgumentException(sprintf('The cookie name "%s" contains invalid characters.', $name));
        }
    }

    /**
     * Validates a value.
     *
     * @see http://tools.ietf.org/html/rfc6265#section-4.1.1
     *
     * @param string|null $value
     *
     * @throws \InvalidArgumentException if the value contains invalid characters
     */
    private function validateValue($value)
    {
        if (isset($value)) {
            if (preg_match('/[^\x21\x23-\x2B\x2D-\x3A\x3C-\x5B\x5D-\x7E]/', $value)) {
                throw new \InvalidArgumentException(sprintf('The cookie value "%s" contains invalid characters.', $value));
            }
        }
    }

    /**
     * Validates a Max-Age attribute.
     *
     * @param int|null $maxAge
     *
     * @throws \InvalidArgumentException if the Max-Age is not an empty or integer value
     */
    private function validateMaxAge($maxAge)
    {
        if (isset($maxAge)) {
            if (!is_int($maxAge)) {
                throw new \InvalidArgumentException('Max-Age must be integer');
            }
        }
    }

    /**
     * Remove the leading '.' and lowercase the domain as per spec in RFC 6265.
     *
     * @see http://tools.ietf.org/html/rfc6265#section-4.1.2.3
     * @see http://tools.ietf.org/html/rfc6265#section-5.1.3
     * @see http://tools.ietf.org/html/rfc6265#section-5.2.3
     *
     * @param string|null $domain
     *
     * @return string
     */
    private function normalizeDomain($domain)
    {
        if (isset($domain)) {
            $domain = ltrim(strtolower($domain), '.');
        }

        return $domain;
    }

    /**
     * Processes path as per spec in RFC 6265.
     *
     * @see http://tools.ietf.org/html/rfc6265#section-5.1.4
     * @see http://tools.ietf.org/html/rfc6265#section-5.2.4
     *
     * @param string|null $path
     *
     * @return string
     */
    private function normalizePath($path)
    {
        $path = rtrim($path, '/');

        if (empty($path) or '/' !== substr($path, 0, 1)) {
            $path = '/';
        }

        return $path;
    }
}
