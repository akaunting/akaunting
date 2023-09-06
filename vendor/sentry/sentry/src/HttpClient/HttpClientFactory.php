<?php

declare(strict_types=1);

namespace Sentry\HttpClient;

use GuzzleHttp\RequestOptions as GuzzleHttpClientOptions;
use Http\Adapter\Guzzle6\Client as Guzzle6HttpClient;
use Http\Adapter\Guzzle7\Client as Guzzle7HttpClient;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\DecoderPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\Plugin\RetryPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Curl\Client as CurlHttpClient;
use Http\Client\HttpAsyncClient as HttpAsyncClientInterface;
use Http\Discovery\HttpAsyncClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Sentry\HttpClient\Authentication\SentryAuthentication;
use Sentry\HttpClient\Plugin\GzipEncoderPlugin;
use Sentry\Options;
use Symfony\Component\HttpClient\HttpClient as SymfonyHttpClient;
use Symfony\Component\HttpClient\HttplugClient as SymfonyHttplugClient;

/**
 * Default implementation of the {@HttpClientFactoryInterface} interface that uses
 * Httplug to autodiscover the HTTP client if none is passed by the user.
 */
final class HttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * @var StreamFactoryInterface The PSR-17 stream factory
     */
    private $streamFactory;

    /**
     * @var HttpAsyncClientInterface|null The HTTP client
     */
    private $httpClient;

    /**
     * @var string The name of the SDK
     */
    private $sdkIdentifier;

    /**
     * @var string The version of the SDK
     */
    private $sdkVersion;

    /**
     * Constructor.
     *
     * @param UriFactoryInterface|null      $uriFactory      The PSR-7 URI factory
     * @param ResponseFactoryInterface|null $responseFactory The PSR-7 response factory
     * @param StreamFactoryInterface        $streamFactory   The PSR-17 stream factory
     * @param HttpAsyncClientInterface|null $httpClient      The HTTP client
     * @param string                        $sdkIdentifier   The SDK identifier
     * @param string                        $sdkVersion      The SDK version
     */
    public function __construct(
        ?UriFactoryInterface $uriFactory,
        ?ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        ?HttpAsyncClientInterface $httpClient,
        string $sdkIdentifier,
        string $sdkVersion
    ) {
        $this->streamFactory = $streamFactory;
        $this->httpClient = $httpClient;
        $this->sdkIdentifier = $sdkIdentifier;
        $this->sdkVersion = $sdkVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Options $options): HttpAsyncClientInterface
    {
        if (null === $options->getDsn()) {
            throw new \RuntimeException('Cannot create an HTTP client without the Sentry DSN set in the options.');
        }

        if (null !== $this->httpClient && null !== $options->getHttpProxy()) {
            throw new \RuntimeException('The "http_proxy" option does not work together with a custom HTTP client.');
        }

        $httpClient = $this->httpClient ?? $this->resolveClient($options);

        $httpClientPlugins = [
            new HeaderSetPlugin(['User-Agent' => $this->sdkIdentifier . '/' . $this->sdkVersion]),
            new AuthenticationPlugin(new SentryAuthentication($options, $this->sdkIdentifier, $this->sdkVersion)),
            new RetryPlugin(['retries' => $options->getSendAttempts(false)]),
            new ErrorPlugin(['only_server_exception' => true]),
        ];

        if ($options->isCompressionEnabled()) {
            $httpClientPlugins[] = new GzipEncoderPlugin($this->streamFactory);
            $httpClientPlugins[] = new DecoderPlugin();
        }

        return new PluginClient($httpClient, $httpClientPlugins);
    }

    /**
     * @return ClientInterface|HttpAsyncClientInterface
     */
    private function resolveClient(Options $options)
    {
        if (class_exists(SymfonyHttplugClient::class)) {
            $symfonyConfig = [
                'timeout' => $options->getHttpConnectTimeout(),
                'max_duration' => $options->getHttpTimeout(),
                'http_version' => $options->isCompressionEnabled() ? '1.1' : null,
            ];

            if (null !== $options->getHttpProxy()) {
                $symfonyConfig['proxy'] = $options->getHttpProxy();
            }

            return new SymfonyHttplugClient(SymfonyHttpClient::create($symfonyConfig));
        }

        if (class_exists(Guzzle7HttpClient::class) || class_exists(Guzzle6HttpClient::class)) {
            $guzzleConfig = [
                GuzzleHttpClientOptions::TIMEOUT => $options->getHttpTimeout(),
                GuzzleHttpClientOptions::CONNECT_TIMEOUT => $options->getHttpConnectTimeout(),
            ];

            if (null !== $options->getHttpProxy()) {
                $guzzleConfig[GuzzleHttpClientOptions::PROXY] = $options->getHttpProxy();
            }

            if (class_exists(Guzzle7HttpClient::class)) {
                return Guzzle7HttpClient::createWithConfig($guzzleConfig);
            }

            return Guzzle6HttpClient::createWithConfig($guzzleConfig);
        }

        if (class_exists(CurlHttpClient::class)) {
            $curlConfig = [
                \CURLOPT_TIMEOUT => $options->getHttpTimeout(),
                \CURLOPT_HTTP_VERSION => $options->isCompressionEnabled() ? \CURL_HTTP_VERSION_1_1 : \CURL_HTTP_VERSION_NONE,
                \CURLOPT_CONNECTTIMEOUT => $options->getHttpConnectTimeout(),
            ];

            if (null !== $options->getHttpProxy()) {
                $curlConfig[\CURLOPT_PROXY] = $options->getHttpProxy();
            }

            return new CurlHttpClient(null, null, $curlConfig);
        }

        if (null !== $options->getHttpProxy()) {
            throw new \RuntimeException('The "http_proxy" option requires either the "php-http/curl-client", the "symfony/http-client" or the "php-http/guzzle6-adapter" package to be installed.');
        }

        return HttpAsyncClientDiscovery::find();
    }
}
