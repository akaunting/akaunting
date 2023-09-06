<?php

namespace Http\Discovery;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * A generic PSR-17 implementation.
 *
 * You can create this class with concrete factory instances or let
 * it use discovery to find suitable implementations as needed.
 *
 * This class also provides two additional methods that are not in PSR17,
 * to help with creating PSR-7 objects from PHP superglobals:
 *  - createServerRequestFromGlobals()
 *  - createUriFromGlobals()
 *
 * The code in this class is inspired by the "nyholm/psr7", "guzzlehttp/psr7"
 * and "symfony/http-foundation" packages, all licenced under MIT.
 *
 * Copyright (c) 2004-2023 Fabien Potencier <fabien@symfony.com>
 * Copyright (c) 2015 Michael Dowling <mtdowling@gmail.com>
 * Copyright (c) 2015 Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * Copyright (c) 2015 Graham Campbell <hello@gjcampbell.co.uk>
 * Copyright (c) 2016 Tobias Schultze <webmaster@tubo-world.de>
 * Copyright (c) 2016 George Mponos <gmponos@gmail.com>
 * Copyright (c) 2016-2018 Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class Psr17Factory implements RequestFactoryInterface, ResponseFactoryInterface, ServerRequestFactoryInterface, StreamFactoryInterface, UploadedFileFactoryInterface, UriFactoryInterface
{
    private $requestFactory;
    private $responseFactory;
    private $serverRequestFactory;
    private $streamFactory;
    private $uploadedFileFactory;
    private $uriFactory;

    public function __construct(
        RequestFactoryInterface $requestFactory = null,
        ResponseFactoryInterface $responseFactory = null,
        ServerRequestFactoryInterface $serverRequestFactory = null,
        StreamFactoryInterface $streamFactory = null,
        UploadedFileFactoryInterface $uploadedFileFactory = null,
        UriFactoryInterface $uriFactory = null
    ) {
        $this->requestFactory = $requestFactory;
        $this->responseFactory = $responseFactory;
        $this->serverRequestFactory = $serverRequestFactory;
        $this->streamFactory = $streamFactory;
        $this->uploadedFileFactory = $uploadedFileFactory;
        $this->uriFactory = $uriFactory;

        $this->setFactory($requestFactory);
        $this->setFactory($responseFactory);
        $this->setFactory($serverRequestFactory);
        $this->setFactory($streamFactory);
        $this->setFactory($uploadedFileFactory);
        $this->setFactory($uriFactory);
    }

    /**
     * @param UriInterface|string $uri
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        $factory = $this->requestFactory ?? $this->setFactory(Psr17FactoryDiscovery::findRequestFactory());

        return $factory->createRequest(...\func_get_args());
    }

    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $factory = $this->responseFactory ?? $this->setFactory(Psr17FactoryDiscovery::findResponseFactory());

        return $factory->createResponse(...\func_get_args());
    }

    /**
     * @param UriInterface|string $uri
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $factory = $this->serverRequestFactory ?? $this->setFactory(Psr17FactoryDiscovery::findServerRequestFactory());

        return $factory->createServerRequest(...\func_get_args());
    }

    public function createServerRequestFromGlobals(array $server = null, array $get = null, array $post = null, array $cookie = null, array $files = null, StreamInterface $body = null): ServerRequestInterface
    {
        $server = $server ?? $_SERVER;
        $request = $this->createServerRequest($server['REQUEST_METHOD'] ?? 'GET', $this->createUriFromGlobals($server), $server);

        return $this->buildServerRequestFromGlobals($request, $server, $files ?? $_FILES)
            ->withQueryParams($get ?? $_GET)
            ->withParsedBody($post ?? $_POST)
            ->withCookieParams($cookie ?? $_COOKIE)
            ->withBody($body ?? $this->createStreamFromFile('php://input', 'r+'));
    }

    public function createStream(string $content = ''): StreamInterface
    {
        $factory = $this->streamFactory ?? $this->setFactory(Psr17FactoryDiscovery::findStreamFactory());

        return $factory->createStream($content);
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $factory = $this->streamFactory ?? $this->setFactory(Psr17FactoryDiscovery::findStreamFactory());

        return $factory->createStreamFromFile($filename, $mode);
    }

    /**
     * @param resource $resource
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        $factory = $this->streamFactory ?? $this->setFactory(Psr17FactoryDiscovery::findStreamFactory());

        return $factory->createStreamFromResource($resource);
    }

    public function createUploadedFile(StreamInterface $stream, int $size = null, int $error = \UPLOAD_ERR_OK, string $clientFilename = null, string $clientMediaType = null): UploadedFileInterface
    {
        $factory = $this->uploadedFileFactory ?? $this->setFactory(Psr17FactoryDiscovery::findUploadedFileFactory());

        return $factory->createUploadedFile(...\func_get_args());
    }

    public function createUri(string $uri = ''): UriInterface
    {
        $factory = $this->uriFactory ?? $this->setFactory(Psr17FactoryDiscovery::findUriFactory());

        return $factory->createUri(...\func_get_args());
    }

    public function createUriFromGlobals(array $server = null): UriInterface
    {
        return $this->buildUriFromGlobals($this->createUri(''), $server ?? $_SERVER);
    }

    private function setFactory($factory)
    {
        if (!$this->requestFactory && $factory instanceof RequestFactoryInterface) {
            $this->requestFactory = $factory;
        }
        if (!$this->responseFactory && $factory instanceof ResponseFactoryInterface) {
            $this->responseFactory = $factory;
        }
        if (!$this->serverRequestFactory && $factory instanceof ServerRequestFactoryInterface) {
            $this->serverRequestFactory = $factory;
        }
        if (!$this->streamFactory && $factory instanceof StreamFactoryInterface) {
            $this->streamFactory = $factory;
        }
        if (!$this->uploadedFileFactory && $factory instanceof UploadedFileFactoryInterface) {
            $this->uploadedFileFactory = $factory;
        }
        if (!$this->uriFactory && $factory instanceof UriFactoryInterface) {
            $this->uriFactory = $factory;
        }

        return $factory;
    }

    private function buildServerRequestFromGlobals(ServerRequestInterface $request, array $server, array $files): ServerRequestInterface
    {
        $request = $request
            ->withProtocolVersion(isset($server['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $server['SERVER_PROTOCOL']) : '1.1')
            ->withUploadedFiles($this->normalizeFiles($files));

        $headers = [];
        foreach ($server as $k => $v) {
            if (0 === strpos($k, 'HTTP_')) {
                $k = substr($k, 5);
            } elseif (!\in_array($k, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                continue;
            }
            $k = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $k))));

            $headers[$k] = $v;
        }

        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $headers['Authorization'] = 'Basic '.base64_encode($_SERVER['PHP_AUTH_USER'].':'.($_SERVER['PHP_AUTH_PW'] ?? ''));
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }

        foreach ($headers as $k => $v) {
            try {
                $request = $request->withHeader($k, $v);
            } catch (\InvalidArgumentException $e) {
                // ignore invalid headers
            }
        }

        return $request;
    }

    private function buildUriFromGlobals(UriInterface $uri, array $server): UriInterface
    {
        $uri = $uri->withScheme(!empty($server['HTTPS']) && 'off' !== strtolower($server['HTTPS']) ? 'https' : 'http');

        $hasPort = false;
        if (isset($server['HTTP_HOST'])) {
            $parts = parse_url('http://'.$server['HTTP_HOST']);

            $uri = $uri->withHost($parts['host'] ?? 'localhost');

            if ($parts['port'] ?? false) {
                $hasPort = true;
                $uri = $uri->withPort($parts['port']);
            }
        } else {
            $uri = $uri->withHost($server['SERVER_NAME'] ?? $server['SERVER_ADDR'] ?? 'localhost');
        }

        if (!$hasPort && isset($server['SERVER_PORT'])) {
            $uri = $uri->withPort($server['SERVER_PORT']);
        }

        $hasQuery = false;
        if (isset($server['REQUEST_URI'])) {
            $requestUriParts = explode('?', $server['REQUEST_URI'], 2);
            $uri = $uri->withPath($requestUriParts[0]);
            if (isset($requestUriParts[1])) {
                $hasQuery = true;
                $uri = $uri->withQuery($requestUriParts[1]);
            }
        }

        if (!$hasQuery && isset($server['QUERY_STRING'])) {
            $uri = $uri->withQuery($server['QUERY_STRING']);
        }

        return $uri;
    }

    private function normalizeFiles(array $files): array
    {
        foreach ($files as $k => $v) {
            if ($v instanceof UploadedFileInterface) {
                continue;
            }
            if (!\is_array($v)) {
                unset($files[$k]);
            } elseif (!isset($v['tmp_name'])) {
                $files[$k] = $this->normalizeFiles($v);
            } else {
                $files[$k] = $this->createUploadedFileFromSpec($v);
            }
        }

        return $files;
    }

    /**
     * Create and return an UploadedFile instance from a $_FILES specification.
     *
     * @param array $value $_FILES struct
     *
     * @return UploadedFileInterface|UploadedFileInterface[]
     */
    private function createUploadedFileFromSpec(array $value)
    {
        if (!is_array($tmpName = $value['tmp_name'])) {
            $file = is_file($tmpName) ? $this->createStreamFromFile($tmpName, 'r') : $this->createStream();

            return $this->createUploadedFile($file, $value['size'], $value['error'], $value['name'], $value['type']);
        }

        foreach ($tmpName as $k => $v) {
            $tmpName[$k] = $this->createUploadedFileFromSpec([
                'tmp_name' => $v,
                'size' => $value['size'][$k] ?? null,
                'error' => $value['error'][$k] ?? null,
                'name' => $value['name'][$k] ?? null,
                'type' => $value['type'][$k] ?? null,
            ]);
        }

        return $tmpName;
    }
}
