<?php

declare(strict_types=1);

namespace Http\Client\Common\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Prepend a base path to the request URI. Useful for base API URLs like http://domain.com/api.
 *
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
final class AddPathPlugin implements Plugin
{
    /**
     * @var UriInterface
     */
    private $uri;

    public function __construct(UriInterface $uri)
    {
        if ('' === $uri->getPath()) {
            throw new \LogicException('URI path cannot be empty');
        }

        if ('/' === substr($uri->getPath(), -1)) {
            $uri = $uri->withPath(rtrim($uri->getPath(), '/'));
        }

        $this->uri = $uri;
    }

    /**
     * Adds a prefix in the beginning of the URL's path.
     *
     * The prefix is not added if that prefix is already on the URL's path. This will fail on the edge
     * case of the prefix being repeated, for example if `https://example.com/api/api/foo` is a valid
     * URL on the server and the configured prefix is `/api`.
     *
     * We looked at other solutions, but they are all much more complicated, while still having edge
     * cases:
     * - Doing an spl_object_hash on `$first` will lead to collisions over time because over time the
     *   hash can collide.
     * - Have the PluginClient provide a magic header to identify the request chain and only apply
     *   this plugin once.
     *
     * There are 2 reasons for the AddPathPlugin to be executed twice on the same request:
     * - A plugin can restart the chain by calling `$first`, e.g. redirect
     * - A plugin can call `$next` more than once, e.g. retry
     *
     * Depending on the scenario, the path should or should not be added. E.g. `$first` could
     * be called after a redirect response from the server. The server likely already has the
     * correct path.
     *
     * No solution fits all use cases. This implementation will work fine for the common use cases.
     * If you have a specific situation where this is not the right thing, you can build a custom plugin
     * that does exactly what you need.
     *
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $prepend = $this->uri->getPath();
        $path = $request->getUri()->getPath();

        if (substr($path, 0, strlen($prepend)) !== $prepend) {
            $request = $request->withUri($request->getUri()
                 ->withPath($prepend.$path)
            );
        }

        return $next($request);
    }
}
