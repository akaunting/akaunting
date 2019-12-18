<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait SiteApi
{
    public static $base_uri = 'https://api.akaunting.com/';

    protected static function siteApiRequest($method, $path, $extra_data = [])
    {
        $client = new Client(['verify' => false, 'base_uri' => static::$base_uri]);

        $headers['headers'] = [
            'Authorization' => 'Bearer ' . setting('apps.api_key'),
            'Accept'        => 'application/json',
            'Referer'       => app()->runningInConsole() ? config('app.url') : url('/'),
            'Akaunting'     => version('short'),
            'Language'      => language()->getShortCode(),
        ];

        $data = array_merge([
            'timeout' => 30,
            'referer' => true,
            'http_errors' => false,
        ], $extra_data);

        $options = array_merge($data, $headers);

        try {
            $response = $client->request($method, $path, $options);
        } catch (RequestException $e) {
            $response = $e;
        }

        return $response;
    }

    public static function getResponse($method, $path, $data = [])
    {
        $response = static::siteApiRequest($method, $path, $data);

        if (!$response || ($response instanceof RequestException) || ($response->getStatusCode() != 200)) {
            return false;
        }

        return $response;
    }

    public static function getResponseData($method, $path, $data = [])
    {
        if (!$response = static::getResponse($method, $path, $data)) {
            return [];
        }

        $body = json_decode($response->getBody());

        if (!is_object($body)) {
            return [];
        }

        return $body->data;
    }
}
