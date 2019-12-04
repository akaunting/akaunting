<?php

namespace App\Utilities;

use App\Events\Install\UpdateCopied;
use App\Events\Install\UpdateDownloaded;
use App\Events\Install\UpdateUnzipped;
use App\Utilities\Console;
use App\Traits\SiteApi;
use Artisan;
use Cache;
use Date;
use File;
use ZipArchive;

class Updater
{
    use SiteApi;

    public static function clear()
    {
        Artisan::call('cache:clear');

        return true;
    }

    public static function download($alias, $new, $old)
    {
        $file = null;
        $path = null;

        // Check core first
        $info = Info::all();

        if ($alias == 'core') {
            $url = 'core/download/' . $new . '/' . $info['php'] . '/' . $info['mysql'];
        } else {
            $url = 'apps/' . $alias . '/download/' . $new . '/' . $info['akaunting'] . '/' . $info['token'];
        }

        if (!$response = static::getResponse('GET', $url, ['timeout' => 50, 'track_redirects' => true])) {
            throw new \Exception(trans('modules.errors.download', ['module' => $alias]));
        }

        $file = $response->getBody()->getContents();

        $path = 'temp-' . md5(mt_rand());
        $temp_path = storage_path('app/temp') . '/' . $path;

        $file_path = $temp_path . '/upload.zip';

        // Create tmp directory
        if (!File::isDirectory($temp_path)) {
            File::makeDirectory($temp_path);
        }

        // Add content to the Zip file
        $uploaded = is_int(file_put_contents($file_path, $file)) ? true : false;

        if (!$uploaded) {
            throw new \Exception(trans('modules.errors.zip', ['module' => $alias]));
        }

        event(new UpdateDownloaded($alias, $new, $old));

        return $path;
    }

    public static function unzip($path, $alias, $new, $old)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        $file = $temp_path . '/upload.zip';

        // Unzip the file
        $zip = new ZipArchive();

        if (($zip->open($file) !== true) || !$zip->extractTo($temp_path)) {
            throw new \Exception(trans('modules.errors.unzip', ['module' => $alias]));
        }

        $zip->close();

        // Delete zip file
        File::delete($file);

        event(new UpdateUnzipped($alias, $new, $old));

        return $path;
    }

    public static function copyFiles($path, $alias, $new, $old)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        if ($alias == 'core') {
            // Move all files/folders from temp path
            if (!File::copyDirectory($temp_path, base_path())) {
                throw new \Exception(trans('modules.errors.file_copy', ['module' => $alias]));
            }
        } else {
            // Get module instance
            $module = module($alias);

            $module_path = $module->getPath();

            // Create module directory
            if (!File::isDirectory($module_path)) {
                File::makeDirectory($module_path);
            }

            // Move all files/folders from temp path
            if (!File::copyDirectory($temp_path, $module_path)) {
                throw new \Exception(trans('modules.errors.file_copy', ['module' => $alias]));
            }
        }

        // Delete temp directory
        File::deleteDirectory($temp_path);

        event(new UpdateCopied($alias, $new, $old));

        return $path;
    }

    public static function finish($alias, $new, $old)
    {
        // Check if the file mirror was successful
        if (($alias == 'core') && (version('short') != $new)) {
            throw new \Exception(trans('modules.errors.finish', ['module' => $alias]));
        }

        $company_id = session('company_id');

        $command = "php artisan update:finish {$alias} {$company_id} {$new} {$old}";

        if (!Console::run($command)) {
            throw new \Exception(trans('modules.errors.finish', ['module' => $alias]));
        }
    }

    public static function all()
    {
        // Get data from cache
        $data = Cache::get('updates');

        if (!empty($data)) {
            return $data;
        }

        // No data in cache, grab them from remote
        $data = array();

        $modules = module()->all();

        $versions = Versions::latest($modules);

        foreach ($versions as $alias => $version) {
            // Modules come as array
            if ($alias == 'core') {
                if (version_compare(version('short'), $version) != 0) {
                    $data['core'] = $version;
                }
            } else {
                $module = module($alias);

                // Up-to-date
                if (version_compare($module->get('version'), $version) == 0) {
                    continue;
                }

                $data[$alias] = $version;
            }
        }

        Cache::put('updates', $data, Date::now()->addHour(6));

        return $data;
    }
}
