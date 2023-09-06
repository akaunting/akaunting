<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Psr\Http\Client\ClientInterface;

/**
 * Emulates an async HTTP client with the help of a synchronous client.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class EmulatedHttpAsyncClient implements HttpClient, HttpAsyncClient
{
    use HttpAsyncClientEmulator;
    use HttpClientDecorator;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
}
