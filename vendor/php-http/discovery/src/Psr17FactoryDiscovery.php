<?php

namespace Http\Discovery;

use Http\Discovery\Exception\DiscoveryFailedException;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Finds PSR-17 factories.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Psr17FactoryDiscovery extends ClassDiscovery
{
    private static function createException($type, Exception $e)
    {
        return new \Http\Discovery\Exception\NotFoundException(
            'No PSR-17 '.$type.' found. Install a package from this list: https://packagist.org/providers/psr/http-factory-implementation',
            0,
            $e
        );
    }

    /**
     * @return RequestFactoryInterface
     *
     * @throws Exception\NotFoundException
     */
    public static function findRequestFactory()
    {
        try {
            $messageFactory = static::findOneByType(RequestFactoryInterface::class);
        } catch (DiscoveryFailedException $e) {
            throw self::createException('request factory', $e);
        }

        return static::instantiateClass($messageFactory);
    }

    /**
     * @return ResponseFactoryInterface
     *
     * @throws Exception\NotFoundException
     */
    public static function findResponseFactory()
    {
        try {
            $messageFactory = static::findOneByType(ResponseFactoryInterface::class);
        } catch (DiscoveryFailedException $e) {
            throw self::createException('response factory', $e);
        }

        return static::instantiateClass($messageFactory);
    }

    /**
     * @return ServerRequestFactoryInterface
     *
     * @throws Exception\NotFoundException
     */
    public static function findServerRequestFactory()
    {
        try {
            $messageFactory = static::findOneByType(ServerRequestFactoryInterface::class);
        } catch (DiscoveryFailedException $e) {
            throw self::createException('server request factory', $e);
        }

        return static::instantiateClass($messageFactory);
    }

    /**
     * @return StreamFactoryInterface
     *
     * @throws Exception\NotFoundException
     */
    public static function findStreamFactory()
    {
        try {
            $messageFactory = static::findOneByType(StreamFactoryInterface::class);
        } catch (DiscoveryFailedException $e) {
            throw self::createException('stream factory', $e);
        }

        return static::instantiateClass($messageFactory);
    }

    /**
     * @return UploadedFileFactoryInterface
     *
     * @throws Exception\NotFoundException
     */
    public static function findUploadedFileFactory()
    {
        try {
            $messageFactory = static::findOneByType(UploadedFileFactoryInterface::class);
        } catch (DiscoveryFailedException $e) {
            throw self::createException('uploaded file factory', $e);
        }

        return static::instantiateClass($messageFactory);
    }

    /**
     * @return UriFactoryInterface
     *
     * @throws Exception\NotFoundException
     */
    public static function findUriFactory()
    {
        try {
            $messageFactory = static::findOneByType(UriFactoryInterface::class);
        } catch (DiscoveryFailedException $e) {
            throw self::createException('url factory', $e);
        }

        return static::instantiateClass($messageFactory);
    }

    /**
     * @return UriFactoryInterface
     *
     * @throws Exception\NotFoundException
     *
     * @deprecated This will be removed in 2.0. Consider using the findUriFactory() method.
     */
    public static function findUrlFactory()
    {
        return static::findUriFactory();
    }
}
