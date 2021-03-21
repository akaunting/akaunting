<?php

namespace Enlightn\Enlightn\Analyzers\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait AnalyzesHeaders
{
    /**
     * The Guzzle client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Set the Guzzle client.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Determine if the header(s) exist on the URL.
     *
     * @param string|null $url
     * @param string|array $headers
     * @param array $options
     * @return bool
     */
    protected function headerExistsOnUrl($url, $headers, $options = [])
    {
        if (is_null($url)) {
            // If we can't find the route, we cannot perform this check.
            return false;
        }

        try {
            $response = $this->client->get($url, array_merge(['http_errors' => false], $options));

            return collect($headers)->contains(function ($header) use ($response) {
                return $response->hasHeader($header);
            });
        } catch (GuzzleException $e) {
            return false;
        }
    }

    /**
     * Get the headers on the URL.
     *
     * @param  string|null  $url
     * @param  string  $header
     * @param  array  $options
     * @return array
     */
    protected function getHeadersOnUrl($url, string $header, $options = [])
    {
        if (is_null($url)) {
            // If we can't find the route, we cannot perform this check.
            return [];
        }

        try {
            $response = $this->client->get($url, array_merge(['http_errors' => false], $options));

            return $response->getHeader($header);
        } catch (GuzzleException $e) {
            return [];
        }
    }
}
