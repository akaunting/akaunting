<?php

namespace Http\Message\RequestMatcher;

use Http\Message\RequestMatcher as RequestMatcherInterface;
use Psr\Http\Message\RequestInterface;

/**
 * A port of the Symfony RequestMatcher for PSR-7.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class RequestMatcher implements RequestMatcherInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $host;

    /**
     * @var array
     */
    private $methods = [];

    /**
     * @var string[]
     */
    private $schemes = [];

    /**
     * The regular expressions used for path or host must be specified without delimiter.
     * You do not need to escape the forward slash / to match it.
     *
     * @param string|null          $path    Regular expression for the path
     * @param string|null          $host    Regular expression for the hostname
     * @param string|string[]|null $methods Method or list of methods to match
     * @param string|string[]|null $schemes Scheme or list of schemes to match (e.g. http or https)
     */
    public function __construct($path = null, $host = null, $methods = [], $schemes = [])
    {
        $this->path = $path;
        $this->host = $host;
        $this->methods = array_map('strtoupper', (array) $methods);
        $this->schemes = array_map('strtolower', (array) $schemes);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function matches(RequestInterface $request)
    {
        if ($this->schemes && !in_array($request->getUri()->getScheme(), $this->schemes)) {
            return false;
        }

        if ($this->methods && !in_array($request->getMethod(), $this->methods)) {
            return false;
        }

        if (null !== $this->path && !preg_match('{'.$this->path.'}', rawurldecode($request->getUri()->getPath()))) {
            return false;
        }

        if (null !== $this->host && !preg_match('{'.$this->host.'}i', $request->getUri()->getHost())) {
            return false;
        }

        return true;
    }
}
