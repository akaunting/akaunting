<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;

/**
 * Emulates a synchronous HTTP client with the help of an asynchronous client.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class EmulatedHttpClient implements HttpClient, HttpAsyncClient
{
    use HttpAsyncClientDecorator;
    use HttpClientEmulator;

    public function __construct(HttpAsyncClient $httpAsyncClient)
    {
        $this->httpAsyncClient = $httpAsyncClient;
    }
}
