<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait SiteApi
{

    protected static function getRemote($path, $method = 'GET', $data = [])
    {
        $base = 'https://api.akaunting.com/';

        $client = new Client(['verify' => false, 'base_uri' => $base]);

        $headers['headers'] = [
            'Authorization' => 'Bearer ' . setting('apps.api_key'),
            'Accept'        => 'application/json',
            'Referer'       => url('/'),
            'Akaunting'     => version('short'),
            'Language'      => language()->getShortCode()
        ];

        $data['http_errors'] = false;

        $data = array_merge($data, $headers);

        try {
            $result = $client->request($method, $path, $data);
        } catch (RequestException $e) {
            $result = $e;
        }

        return $result;
    }
}
