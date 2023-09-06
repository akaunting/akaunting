<?php

declare(strict_types=1);

namespace Sentry\Transport;

use Sentry\Options;

/**
 * This interface defines a contract for all classes willing to create instances
 * of the transport to use with the Sentry client.
 */
interface TransportFactoryInterface
{
    /**
     * Creates a new instance of a transport that will be used to send events.
     *
     * @param Options $options The options of the Sentry client
     */
    public function create(Options $options): TransportInterface;
}
