<?php

declare(strict_types=1);

namespace Sentry;

use Psr\Log\LoggerInterface;
use Sentry\Serializer\RepresentationSerializerInterface;
use Sentry\Serializer\SerializerInterface;
use Sentry\Transport\TransportFactoryInterface;

/**
 * A configurable builder for Client objects.
 *
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
interface ClientBuilderInterface
{
    /**
     * Creates a new instance of this builder.
     *
     * @param array<string, mixed> $options The client options, in naked array form
     *
     * @return static
     */
    public static function create(array $options = []): self;

    /**
     * The options that will be used to create the {@see Client}.
     */
    public function getOptions(): Options;

    /**
     * Gets the instance of the client built using the configured options.
     */
    public function getClient(): ClientInterface;

    /**
     * Sets a serializer instance to be injected as a dependency of the client.
     *
     * @param SerializerInterface $serializer The serializer to be used by the client to fill the events
     *
     * @return $this
     */
    public function setSerializer(SerializerInterface $serializer): self;

    /**
     * Sets a representation serializer instance to be injected as a dependency of the client.
     *
     * @param RepresentationSerializerInterface $representationSerializer The representation serializer, used to serialize function
     *                                                                    arguments in stack traces, to have string representation
     *                                                                    of non-string values
     *
     * @return $this
     */
    public function setRepresentationSerializer(RepresentationSerializerInterface $representationSerializer): self;

    /**
     * Sets a PSR-3 logger to log internal debug messages.
     *
     * @param LoggerInterface $logger The logger instance
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger): ClientBuilderInterface;

    /**
     * Sets the transport factory.
     *
     * @param TransportFactoryInterface $transportFactory The transport factory
     *
     * @return $this
     */
    public function setTransportFactory(TransportFactoryInterface $transportFactory): ClientBuilderInterface;

    /**
     * Sets the SDK identifier to be passed onto {@see Event} and HTTP User-Agent header.
     *
     * @param string $sdkIdentifier The SDK identifier to be sent in {@see Event} and HTTP User-Agent headers
     *
     * @return $this
     *
     * @internal
     */
    public function setSdkIdentifier(string $sdkIdentifier): self;

    /**
     * Sets the SDK version to be passed onto {@see Event} and HTTP User-Agent header.
     *
     * @param string $sdkVersion The version of the SDK in use, to be sent alongside the SDK identifier
     *
     * @return $this
     *
     * @internal
     */
    public function setSdkVersion(string $sdkVersion): self;
}
