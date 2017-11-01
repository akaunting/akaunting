<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait SiteApi
{

    protected static function getRemote($url, $data = array())
    {
        $base = 'https://akaunting.com/api/';

        $client = new Client(['verify' => false, 'base_uri' => $base]);

        $headers['headers'] = array(
            'Authorization' => 'Bearer ' . setting('general.api_token'),
            'Accept'        => 'application/json',
            'Referer'       => env('APP_URL'),
        );

        $data['http_errors'] = false;

        $data = array_merge($data, $headers);

        $result = $client->get($url, $data);

        return $result;
    }
}