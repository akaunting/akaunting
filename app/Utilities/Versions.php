<?php

namespace App\Utilities;

use App\Traits\SiteApi;
use Cache;
use Date;

class Versions
{
    use SiteApi;

    public static function changelog()
    {
        $output = '';

        $url = 'https://api.github.com/repos/akaunting/akaunting/releases';

        $http = new \GuzzleHttp\Client(['verify' => false]);

        $json = $http->get($url, ['timeout' => 30])->getBody()->getContents();

        if (empty($json)) {
            return $output;
        }

        $github = new \cebe\markdown\GithubMarkdown();

        $releases = json_decode($json);

        foreach ($releases as $release) {
            if ($release->tag_name <= version('short')) {
                continue;
            }

            if ($release->prerelease == true) {
                continue;
            }

            if (empty($release->body)) {
                continue;
            }

            $output .= '<h2><span class="label label-success">'.$release->tag_name.'</span></h2>';

            // Parse markdown output
            $markdown = str_replace('## Changelog', '', $release->body);

            $output .= $github->parse($markdown);

            $output .= '<hr>';
        }

        return $output;
    }

    public static function latest($modules = array())
    {
        // Get data from cache
        $data = Cache::get('versions');

        if (!empty($data)) {
            return $data;
        }

        $info = Info::all();

        // No data in cache, grab them from remote
        $data = array();

        // Check core first
        $url = 'core/version/' . $info['akaunting'] . '/' . $info['php'] . '/' . $info['mysql'] . '/' . $info['companies'];

        $data['core'] = static::getLatestVersion($url);

        // API token required for modules
        if (!setting('general.api_token')) {
            Cache::put('versions', $data, Date::now()->addHour(6));

            return $data;
        }

        // Then modules
        foreach ($modules as $module) {
            $alias = $module->get('alias');
            $version = $module->get('version');

            $url = 'apps/items/' . $alias . '/version/' . $version . '/' . $info['akaunting'];

            $data[$alias] = static::getLatestVersion($url);
        }

        Cache::put('versions', $data, Date::now()->addHour(6));

        return $data;
    }

    public static function getLatestVersion($url)
    {
        $latest = '0.0.0';

        $response = static::getRemote($url, ['timeout' => 30, 'referer' => true]);

        // Bad response
        if ($response->getStatusCode() != 200) {
            return $latest;
        }

        $content = json_decode($response->getBody());

        // Empty response
        if (!is_object($content) || !is_object($content->data)) {
            return $latest;
        }

        // Get the latest version
        $latest = $content->data->latest;

        return $latest;
    }
}