<?php

namespace Http\Message\StreamFactory;

use GuzzleHttp\Psr7\Utils;
use Http\Message\StreamFactory;

if (!interface_exists(StreamFactory::class)) {
    throw new \LogicException('You cannot use "Http\Message\MessageFactory\GuzzleStreamFactory" as the "php-http/message-factory" package is not installed. Try running "composer require php-http/message-factory". Note that this package is deprecated, use "psr/http-factory" instead');
}

/**
 * Creates Guzzle streams.
 *
 * @author Михаил Красильников <m.krasilnikov@yandex.ru>
 *
 * @deprecated This will be removed in php-http/message2.0. Consider using the official Guzzle PSR-17 factory
 */
final class GuzzleStreamFactory implements StreamFactory
{
    /**
     * {@inheritdoc}
     */
    public function createStream($body = null)
    {
        if (class_exists(Utils::class)) {
            return Utils::streamFor($body);
        }

        // legacy support for guzzle/psr7 1.*
        return \GuzzleHttp\Psr7\stream_for($body);
    }
}
