<?php

namespace Http\Discovery;

use Http\Discovery\Exception\DiscoveryFailedException;
use Http\Message\MessageFactory;

/**
 * Finds a Message Factory.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @deprecated This will be removed in 2.0. Consider using Psr17FactoryDiscovery.
 */
final class MessageFactoryDiscovery extends ClassDiscovery
{
    /**
     * Finds a Message Factory.
     *
     * @return MessageFactory
     *
     * @throws Exception\NotFoundException
     */
    public static function find()
    {
        try {
            $messageFactory = static::findOneByType(MessageFactory::class);
        } catch (DiscoveryFailedException $e) {
            throw new NotFoundException('No php-http message factories found. Note that the php-http message factories are deprecated in favor of the PSR-17 message factories. To use the legacy Guzzle, Diactoros or Slim Framework factories of php-http, install php-http/message and php-http/message-factory and the chosen message implementation.', 0, $e);
        }

        return static::instantiateClass($messageFactory);
    }
}
