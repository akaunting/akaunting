<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Message\RequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class HttpMethodsClient implements HttpMethodsClientInterface
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var RequestFactory|RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface|null
     */
    private $streamFactory;

    /**
     * @param RequestFactory|RequestFactoryInterface $requestFactory
     */
    public function __construct(ClientInterface $httpClient, $requestFactory, StreamFactoryInterface $streamFactory = null)
    {
        if (!$requestFactory instanceof RequestFactory && !$requestFactory instanceof RequestFactoryInterface) {
            throw new \TypeError(
                sprintf('%s::__construct(): Argument #2 ($requestFactory) must be of type %s|%s, %s given', self::class, RequestFactory::class, RequestFactoryInterface::class, get_debug_type($requestFactory))
            );
        }

        if (!$requestFactory instanceof RequestFactory && null === $streamFactory) {
            @trigger_error(sprintf('Passing a %s without a %s to %s::__construct() is deprecated as of version 2.3 and will be disallowed in version 3.0. A stream factory is required to create a request with a non-empty string body.', RequestFactoryInterface::class, StreamFactoryInterface::class, self::class));
        }

        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function get($uri, array $headers = []): ResponseInterface
    {
        return $this->send('GET', $uri, $headers, null);
    }

    public function head($uri, array $headers = []): ResponseInterface
    {
        return $this->send('HEAD', $uri, $headers, null);
    }

    public function trace($uri, array $headers = []): ResponseInterface
    {
        return $this->send('TRACE', $uri, $headers, null);
    }

    public function post($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('POST', $uri, $headers, $body);
    }

    public function put($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('PUT', $uri, $headers, $body);
    }

    public function patch($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('PATCH', $uri, $headers, $body);
    }

    public function delete($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('DELETE', $uri, $headers, $body);
    }

    public function options($uri, array $headers = [], $body = null): ResponseInterface
    {
        return $this->send('OPTIONS', $uri, $headers, $body);
    }

    public function send(string $method, $uri, array $headers = [], $body = null): ResponseInterface
    {
        if (!is_string($uri) && !$uri instanceof UriInterface) {
            throw new \TypeError(
                sprintf('%s::send(): Argument #2 ($uri) must be of type string|%s, %s given', self::class, UriInterface::class, get_debug_type($uri))
            );
        }

        if (!is_string($body) && !$body instanceof StreamInterface && null !== $body) {
            throw new \TypeError(
                sprintf('%s::send(): Argument #4 ($body) must be of type string|%s|null, %s given', self::class, StreamInterface::class, get_debug_type($body))
            );
        }

        return $this->sendRequest(
            self::createRequest($method, $uri, $headers, $body)
        );
    }

    /**
     * @param string|UriInterface         $uri
     * @param string|StreamInterface|null $body
     */
    private function createRequest(string $method, $uri, array $headers = [], $body = null): RequestInterface
    {
        if ($this->requestFactory instanceof RequestFactory) {
            return $this->requestFactory->createRequest(
                $method,
                $uri,
                $headers,
                $body
            );
        }

        $request = $this->requestFactory->createRequest($method, $uri);

        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        if (null !== $body && '' !== $body) {
            if (null === $this->streamFactory) {
                throw new \RuntimeException('Cannot create request: A stream factory is required to create a request with a non-empty string body.');
            }

            $request = $request->withBody(
                is_string($body) ? $this->streamFactory->createStream($body) : $body
            );
        }

        return $request;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }
}
