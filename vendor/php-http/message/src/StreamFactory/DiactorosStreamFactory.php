<?php

namespace Http\Message\StreamFactory;

use Http\Message\StreamFactory;
use Laminas\Diactoros\Stream as LaminasStream;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Stream as ZendStream;

if (!interface_exists(StreamFactory::class)) {
    throw new \LogicException('You cannot use "Http\Message\MessageFactory\DiactorosStreamFactory" as the "php-http/message-factory" package is not installed. Try running "composer require php-http/message-factory". Note that this package is deprecated, use "psr/http-factory" instead');
}

/**
 * Creates Diactoros streams.
 *
 * @author Михаил Красильников <m.krasilnikov@yandex.ru>
 *
 * @deprecated This will be removed in php-http/message2.0. Consider using the official Diactoros PSR-17 factory
 */
final class DiactorosStreamFactory implements StreamFactory
{
    /**
     * {@inheritdoc}
     */
    public function createStream($body = null)
    {
        if ($body instanceof StreamInterface) {
            return $body;
        }

        if (is_resource($body)) {
            if (class_exists(LaminasStream::class)) {
                return new LaminasStream($body);
            }

            return new ZendStream($body);
        }

        if (class_exists(LaminasStream::class)) {
            $stream = new LaminasStream('php://memory', 'rw');
        } else {
            $stream = new ZendStream('php://memory', 'rw');
        }

        if (null !== $body && '' !== $body) {
            $stream->write((string) $body);
        }

        return $stream;
    }
}
