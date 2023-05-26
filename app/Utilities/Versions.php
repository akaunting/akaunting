<?php

namespace App\Utilities;

use App\Traits\SiteApi;
use App\Utilities\Date;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

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

        if (empty($versions[$alias])) {
            return static::getVersionByAlias($alias);
        }

        return $versions[$alias];
    }

    public static function all($modules = null)
    {
        // Get data from cache
        $versions = Cache::get('versions');

        if (! empty($versions)) {
            return $versions;
        }

        $info = Info::all();

        $versions = [];

        // Check core first
        $url = 'core/version/' . $info['akaunting'] . '/' . $info['php'] . '/' . $info['mysql'] . '/' . $info['companies'];

        # Installed modules start
        $modules = Arr::wrap($modules);

        $installed_modules = [];
        $module_version = '?modules=';

        foreach ($modules as $module) {
            if (is_string($module)) {
                $module = module($module);
            }

            if (! $module instanceof \Akaunting\Module\Module) {
                continue;
            }

            $alias = $module->get('alias');

            $installed_modules[] = $alias;
        }

        $module_version .= implode(',', $installed_modules);

        $url .= $module_version;
        # Installed modules end

        $versions['core'] = static::getLatestVersion($url, $info['akaunting']);

        // Then modules
        foreach ($modules as $module) {
            if (is_string($module)) {
                $module = module($module);
            }

            if (! $module instanceof \Akaunting\Module\Module) {
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

    public static function getVersionByAlias($alias)
    {
        $info = Info::all();

        // Check core first
        $url = 'core/version/' . $info['akaunting'] . '/' . $info['php'] . '/' . $info['mysql'] . '/' . $info['companies'];
        $version = $info['akaunting'];

        if ($alias != 'core') {
            $version = module($alias)->get('version');

            $url = 'apps/' . $alias . '/version/' . $version . '/' . $info['akaunting'];
        }

        // Get data from cache
        $versions = Cache::get('versions', []);

        $versions[$alias] = static::getLatestVersion($url, $version);

        Cache::put('versions', $versions, Date::now()->addHour(6));

        return $versions[$alias];
    }

    public static function getLatestVersion($url, $latest)
    {
        $version = new \stdClass();

        $version->can_update = true;
        $version->latest = $latest;
        $version->errors = false;
        $version->message = '';

        if (! $body = static::getResponseBody('GET', $url, ['timeout' => 10])) {
            return $version;
        }

        if (! is_object($body)) {
            return $version;
        }

        $version->can_update = $body->success;
        $version->latest = $body->data->latest;
        $version->errors = $body->errors;
        $version->message = $body->message;

        return $version;
    }

    public static function getUpdates()
    {
        // Get data from cache
        $updates = Cache::get('updates');

        if (! empty($updates)) {
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

            if (version_compare($installed_version, $latest_version->latest, '>=')) {
                continue;
            }

            $updates[$alias] = $latest_version;
        }

        Cache::put('updates', $updates, Date::now()->addHour(6));

        return $updates;
    }

    public static function shouldUpdate($listener_version, $old_version, $new_version): bool
    {
        // Don't update if "listener" is same or lower than "old" version
        if (version_compare($listener_version, $old_version, '<=')) {
            return false;
        }

        // Don't update if "listener" is higher than "new" version
        if (version_compare($listener_version, $new_version, '>')) {
            return false;
        }

        return true;
    }
}
