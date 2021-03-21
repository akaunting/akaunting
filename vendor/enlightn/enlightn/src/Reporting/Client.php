<?php

namespace Enlightn\Enlightn\Reporting;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct(
        $username,
        $apiToken,
        string $baseUrl = 'https://www.laravel-enlightn.com/api/',
        float $timeout = 10.0
    ) {
        $this->client = new GuzzleClient([
            'base_uri' => $baseUrl,
            'auth' => [$username, $apiToken],
            'timeout' => $timeout,
            'http_errors' => false,
        ]);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array|string
     */
    public function get(string $url, array $data = [])
    {
        return $this->request('GET', $url, $data);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array|string
     */
    public function post(string $url, array $data = [])
    {
        return $this->request('POST', $url, $data);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array|string
     */
    public function patch(string $url, array $data = [])
    {
        return $this->request('PATCH', $url, $data);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array|string
     */
    public function put(string $url, array $data = [])
    {
        return $this->request('PUT', $url, $data);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array|string
     */
    public function delete(string $url, array $data = [])
    {
        return $this->request('DELETE', $url, $data);
    }

    /**
     * @param string $httpVerb
     * @param string $url
     * @param array $data
     * @return array|string
     * @throws \Enlightn\Enlightn\Reporting\UnauthorizedException|\Enlightn\Enlightn\Reporting\BadResponseException
     */
    public function request(string $httpVerb, string $url, array $data = [])
    {
        $response = $this->client->request($httpVerb, $url, [
            'json' => $data,
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode === 403 || $statusCode === 401) {
            throw new UnauthorizedException("Invalid credentials. Please check your username and API token.");
        }

        if ($statusCode !== 200 && $statusCode !== 204) {
            throw new BadResponseException("Bad response: ".$response->getBody());
        }

        $responseData = json_decode($response->getBody(), true);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $responseData;
        } else {
            return (string) $response->getBody();
        }
    }
}
