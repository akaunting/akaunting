<?php

declare(strict_types=1);

namespace Sentry\HttpClient\Plugin;

use Http\Client\Common\Plugin as PluginInterface;
use Http\Promise\Promise as PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * This plugin encodes the request body by compressing it with Gzip.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
final class GzipEncoderPlugin implements PluginInterface
{
    /**
     * @var StreamFactoryInterface The PSR-17 stream factory
     */
    private $streamFactory;

    /**
     * Constructor.
     *
     * @param StreamFactoryInterface $streamFactory The stream factory
     *
     * @throws \RuntimeException If the zlib extension is not enabled
     */
    public function __construct(StreamFactoryInterface $streamFactory)
    {
        if (!\extension_loaded('zlib')) {
            throw new \RuntimeException('The "zlib" extension must be enabled to use this plugin.');
        }

        $this->streamFactory = $streamFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): PromiseInterface
    {
        $requestBody = $request->getBody();

        if ($requestBody->isSeekable()) {
            $requestBody->rewind();
        }

        // Instead of using a stream filter we have to compress the whole request
        // body in one go to work around a PHP bug. See https://github.com/getsentry/sentry-php/pull/877
        $encodedBody = gzcompress($requestBody->getContents(), -1, \ZLIB_ENCODING_GZIP);

        if (false === $encodedBody) {
            throw new \RuntimeException('Failed to GZIP-encode the request body.');
        }

        $request = $request->withHeader('Content-Encoding', 'gzip');
        $request = $request->withBody($this->streamFactory->createStream($encodedBody));

        return $next($request);
    }
}
