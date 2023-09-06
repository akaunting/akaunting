<?php

namespace Http\Discovery;

use Http\Discovery\Exception\DiscoveryFailedException;
use Http\Message\StreamFactory;

/**
 * Finds a Stream Factory.
 *
 * @author Михаил Красильников <m.krasilnikov@yandex.ru>
 *
 * @deprecated This will be removed in 2.0. Consider using Psr17FactoryDiscovery.
 */
final class StreamFactoryDiscovery extends ClassDiscovery
{
    /**
     * Finds a Stream Factory.
     *
     * @return StreamFactory
     *
     * @throws Exception\NotFoundException
     */
    public static function find()
    {
        try {
            $streamFactory = static::findOneByType(StreamFactory::class);
        } catch (DiscoveryFailedException $e) {
            throw new NotFoundException('No stream factories found. To use Guzzle, Diactoros or Slim Framework factories install php-http/message and the chosen message implementation.', 0, $e);
        }

        return static::instantiateClass($streamFactory);
    }
}
