<?php

namespace App\Utilities;

use App\Traits\SiteApi;
use Cache;
use Date;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Arr;

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

        $releases = json_decode($json);

        foreach ($releases as $release) {
            if (version_compare($release->tag_name, version('short'), '<=')) {
                continue;
            }

            if ($release->prerelease == true) {
                continue;
            }

            if (empty($release->body)) {
                continue;
            }

            $output .= '<h2><span class="badge badge-pill badge-success">' . $release->tag_name . '</span></h2>';

            $output .= Markdown::convertToHtml($release->body);

            $output .= '<hr>';
        }

        return $output;
    }

    public static function latest($alias)
    {
        $versions = static::all($alias);

        if (empty($versions[$alias]) || empty($versions[$alias]->data)) {
            return false;
        }

        return $versions[$alias]->data->latest;
    }

    public static function all($modules = null)
    {
        // Get data from cache
        $versions = Cache::get('versions');

        if (!empty($versions)) {
            return $versions;
        }

        $info = Info::all();

        $versions = [];

        // Check core first
        $url = 'core/version/' . $info['akaunting'] . '/' . $info['php'] . '/' . $info['mysql'] . '/' . $info['companies'];

        $versions['core'] = static::getLatestVersion($url, $info['akaunting']);

        $modules = Arr::wrap($modules);

        // Then modules
        foreach ($modules as $module) {
            if (is_string($module)) {
                $module = module($module);
            }

            if (!$module instanceof \Akaunting\Module\Module) {
                continue;
            }

            $alias = $module->get('alias');
            $version = $module->get('version');

            $url = 'apps/' . $alias . '/version/' . $version . '/' . $info['akaunting'];

            $versions[$alias] = static::getLatestVersion($url, $version);
        }

        Cache::put('versions', $versions, Date::now()->addHour(6));

        return $versions;
    }

    public static function getLatestVersion($url, $latest)
    {
        if (!$data = static::getResponseData('GET', $url, ['timeout' => 10])) {
            return $latest;
        }

        if (!is_object($data)) {
            return $latest;
        }

        return $data->latest;
    }

    public static function getUpdates()
    {
        // Get data from cache
        $updates = Cache::get('updates');

        if (!empty($updates)) {
            return $updates;
        }

        $updates = [];

        $modules = module()->all();

        $versions = static::all($modules);

        foreach ($versions as $alias => $latest_version) {
            if ($alias == 'core') {
                $installed_version = version('short');
            } else {
                $module = module($alias);

                if (!$module instanceof \Akaunting\Module\Module) {
                    continue;
                }

                $installed_version = $module->get('version');
            }

            if (version_compare($installed_version, $latest_version, '>=')) {
                continue;
            }

            $updates[$alias] = $latest_version;
        }

        Cache::put('updates', $updates, Date::now()->addHour(6));

        return $updates;
    }
}
