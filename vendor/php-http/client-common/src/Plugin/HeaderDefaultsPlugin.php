<?php

declare(strict_types=1);

namespace Http\Client\Common\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

/**
 * Set header to default value if it does not exist.
 *
 * If a given header already exists the value wont be replaced and the request wont be changed.
 *
 * @author Soufiane Ghzal <sghzal@gmail.com>
 */
final class HeaderDefaultsPlugin implements Plugin
{
    /**
     * @var array
     */
    private $headers = [];

    /**
     * @param array $headers Hashmap of header name to header value
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        foreach ($this->headers as $header => $headerValue) {
            if (!$request->hasHeader($header)) {
                $request = $request->withHeader($header, $headerValue);
            }
        }

        return $next($request);
    }
}
