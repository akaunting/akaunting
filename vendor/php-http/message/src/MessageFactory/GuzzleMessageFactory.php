<?php

namespace Http\Message\MessageFactory;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Message\MessageFactory;

if (!interface_exists(MessageFactory::class)) {
    throw new \LogicException('You cannot use "Http\Message\MessageFactory\GuzzleMessageFactory" as the "php-http/message-factory" package is not installed. Try running "composer require php-http/message-factory". Note that this package is deprecated, use "psr/http-factory" instead');
}

/**
 * Creates Guzzle messages.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @deprecated This will be removed in php-http/message2.0. Consider using the official Guzzle PSR-17 factory
 */
final class GuzzleMessageFactory implements MessageFactory
{
    /**
     * {@inheritdoc}
     */
    public function createRequest(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return new Request(
            $method,
            $uri,
            $headers,
            $body,
            $protocolVersion
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return new Response(
            $statusCode,
            $headers,
            $body,
            $protocolVersion,
            $reasonPhrase
        );
    }
}
