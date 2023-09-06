<?php

declare(strict_types=1);

namespace Http\Client\Common\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

/**
 * Removes headers from the request.
 *
 * @author Soufiane Ghzal <sghzal@gmail.com>
 */
final class HeaderRemovePlugin implements Plugin
{
    /**
     * @var array
     */
    private $headers = [];

    /**
     * @param array $headers List of header names to remove from the request
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
        foreach ($this->headers as $header) {
            if ($request->hasHeader($header)) {
                $request = $request->withoutHeader($header);
            }
        }

        return $next($request);
    }
}
