<?php

declare(strict_types=1);

namespace Sentry\Integration;

use Http\Discovery\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Default implementation for RequestFetcherInterface. Creates a request object
 * from the PHP superglobals.
 */
final class RequestFetcher implements RequestFetcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function fetchRequest(): ?ServerRequestInterface
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || \PHP_SAPI === 'cli') {
            return null;
        }

        return (new Psr17Factory())->createServerRequestFromGlobals();
    }
}
