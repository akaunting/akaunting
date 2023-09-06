<?php

declare(strict_types=1);

namespace Http\Client\Common\Plugin;

use Http\Message\Stream\BufferedStream;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

/**
 * Allow body used in request to be always seekable.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class RequestSeekableBodyPlugin extends SeekableBodyPlugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if (!$request->getBody()->isSeekable()) {
            $request = $request->withBody(new BufferedStream($request->getBody(), $this->useFileBuffer, $this->memoryBufferSize));
        }

        return $next($request);
    }
}
