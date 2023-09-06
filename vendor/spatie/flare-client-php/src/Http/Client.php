<?php

namespace Spatie\FlareClient\Http;

use Spatie\FlareClient\Http\Exceptions\BadResponseCode;
use Spatie\FlareClient\Http\Exceptions\InvalidData;
use Spatie\FlareClient\Http\Exceptions\MissingParameter;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class Client
{
    protected ?string $apiToken;

    protected ?string $baseUrl;

    protected int $timeout;

    protected $lastRequest = null;

    public function __construct(
        ?string $apiToken = null,
        string $baseUrl = 'https://reporting.flareapp.io/api',
        int $timeout = 10
    ) {
        $this->apiToken = $apiToken;

        if (! $baseUrl) {
            throw MissingParameter::create('baseUrl');
        }

        $this->baseUrl = $baseUrl;

        if (! $timeout) {
            throw MissingParameter::create('timeout');
        }

        $this->timeout = $timeout;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function apiTokenSet(): bool
    {
        return ! empty($this->apiToken);
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @param string $url
     * @param array  $arguments
     *
     * @return array|false
     */
    public function get(string $url, array $arguments = [])
    {
        return $this->makeRequest('get', $url, $arguments);
    }

    /**
     * @param string $url
     * @param array  $arguments
     *
     * @return array|false
     */
    public function post(string $url, array $arguments = [])
    {
        return $this->makeRequest('post', $url, $arguments);
    }

    /**
     * @param string $url
     * @param array  $arguments
     *
     * @return array|false
     */
    public function patch(string $url, array $arguments = [])
    {
        return $this->makeRequest('patch', $url, $arguments);
    }

    /**
     * @param string $url
     * @param array  $arguments
     *
     * @return array|false
     */
    public function put(string $url, array $arguments = [])
    {
        return $this->makeRequest('put', $url, $arguments);
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return array|false
     */
    public function delete(string $method, array $arguments = [])
    {
        return $this->makeRequest('delete', $method, $arguments);
    }

    /**
     * @param string $httpVerb
     * @param string $url
     * @param array $arguments
     *
     * @return array
     */
    protected function makeRequest(string $httpVerb, string $url, array $arguments = [])
    {
        $queryString = http_build_query([
            'key' => $this->apiToken,
        ]);

        $fullUrl = "{$this->baseUrl}/{$url}?{$queryString}";

        $headers = [
            'x-api-token: '.$this->apiToken,
        ];

        $response = $this->makeCurlRequest($httpVerb, $fullUrl, $headers, $arguments);

        if ($response->getHttpResponseCode() === 422) {
            throw InvalidData::createForResponse($response);
        }

        if ($response->getHttpResponseCode() === 404) {
            throw NotFound::createForResponse($response);
        }

        if ($response->getHttpResponseCode() !== 200 && $response->getHttpResponseCode() !== 204) {
            throw BadResponseCode::createForResponse($response);
        }

        return $response->getBody();
    }

    public function makeCurlRequest(string $httpVerb, string $fullUrl, array $headers = [], array $arguments = []): Response
    {
        $curlHandle = $this->getCurlHandle($fullUrl, $headers);

        switch ($httpVerb) {
            case 'post':
                curl_setopt($curlHandle, CURLOPT_POST, true);
                $this->attachRequestPayload($curlHandle, $arguments);

                break;

            case 'get':
                curl_setopt($curlHandle, CURLOPT_URL, $fullUrl.'&'.http_build_query($arguments));

                break;

            case 'delete':
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'DELETE');

                break;

            case 'patch':
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'PATCH');
                $this->attachRequestPayload($curlHandle, $arguments);

                break;

            case 'put':
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'PUT');
                $this->attachRequestPayload($curlHandle, $arguments);

                break;
        }

        $body = json_decode(curl_exec($curlHandle), true);
        $headers = curl_getinfo($curlHandle);
        $error = curl_error($curlHandle);

        return new Response($headers, $body, $error);
    }

    protected function attachRequestPayload(&$curlHandle, array $data)
    {
        $encoded = json_encode($data);

        $this->lastRequest['body'] = $encoded;
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $encoded);
    }

    /**
     * @param string $fullUrl
     * @param array $headers
     *
     * @return resource
     */
    protected function getCurlHandle(string $fullUrl, array $headers = [])
    {
        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $fullUrl);

        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array_merge([
            'Accept: application/json',
            'Content-Type: application/json',
        ], $headers));

        curl_setopt($curlHandle, CURLOPT_USERAGENT, 'Laravel/Flare API 1.0');
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curlHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curlHandle, CURLOPT_ENCODING, '');
        curl_setopt($curlHandle, CURLINFO_HEADER_OUT, true);
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlHandle, CURLOPT_MAXREDIRS, 1);

        return $curlHandle;
    }
}
