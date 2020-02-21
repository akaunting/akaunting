<?php

namespace App\Utilities;

use App\Events\Install\UpdateCopied;
use App\Events\Install\UpdateDownloaded;
use App\Events\Install\UpdateUnzipped;
use App\Models\Module\Module;
use App\Utilities\Console;
use App\Traits\SiteApi;
use Artisan;
use Cache;
use Date;
use File;
use Illuminate\Support\Str;
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
            $url = 'apps/' . $alias . '/download/' . $new . '/' . $info['akaunting'] . '/' . $info['api_key'];
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
            if ($module = module($alias)) {
                $module_path = $module->getPath();
            } else {
                $module_path = base_path('modules/' . Str::studly($alias));
            }

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
        if ($alias == 'core') {
            $companies = [session('company_id')];
        } else {
            $companies = Module::alias($alias)->where('company_id', '<>', '0')->pluck('company_id')->toArray();
        }

        foreach ($companies as $company) {
            $command = "php artisan update:finish {$alias} {$company} {$new} {$old}";

            if (true !== $result = Console::run($command)) {
                $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $alias]);

                throw new \Exception($message);
            }
        }
    }

    public static function all()
    {
        // Get data from cache
        $updates = Cache::get('updates');

        if (!empty($updates)) {
            return $updates;
        }

        $updates = [];

        $modules = module()->all();

        $versions = Versions::all($modules);

        foreach ($versions as $alias => $latest_version) {
            $installed_version = ($alias == 'core') ? version('short') : module($alias)->get('version');

            if (version_compare($installed_version, $latest_version, '>=')) {
                continue;
            }

            $updates[$alias] = $latest_version;
        }

        Cache::put('updates', $updates, Date::now()->addHour(6));

        return $updates;
    }
}
