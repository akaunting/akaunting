<?php

namespace App\Utilities;

use App\Events\UpdateFinished;
use App\Models\Module\Module as Model;
use App\Models\Module\ModuleHistory as ModelHistory;
use App\Traits\SiteApi;
use Cache;
use Date;
use File;
use Module;
use ZipArchive;
use Artisan;
use GuzzleHttp\Exception\RequestException;

class Updater
{
    use SiteApi;

    public static function clear()
    {
        Artisan::call('cache:clear');

        return true;
    }

    // Update
    public static function update($alias, $version)
    {
        // Download file
        if (!$data = static::download($alias, $version)) {
            return false;
        }

        // Create temp directory
        $path = 'temp-' . md5(mt_rand());
        $temp_path = storage_path('app/temp') . '/' . $path;

        if (!File::isDirectory($temp_path)) {
            File::makeDirectory($temp_path);
        }

        $file = $temp_path . '/upload.zip';

        // Add content to the Zip file
        $uploaded = is_int(file_put_contents($file, $data)) ? true : false;

        if (!$uploaded) {
            return false;
        }

        // Unzip the file
        $zip = new ZipArchive();

        if (($zip->open($file) !== true) || !$zip->extractTo($temp_path)) {
            return false;
        }

        $zip->close();

        // Delete zip file
        File::delete($file);

        if ($alias == 'core') {
            // Move all files/folders from temp path
            if (!File::copyDirectory($temp_path, base_path())) {
                return false;
            }
        } else {
            // Get module instance
            $module = Module::findByAlias($alias);
            $model = Model::where('alias', $alias)->first();

            // Move all files/folders from temp path
            if (!File::copyDirectory($temp_path, module_path($module->get('name')))) {
                return false;
            }

            // Add history
            ModelHistory::create([
                'company_id' => session('company_id'),
                'module_id' => $model->id,
                'category' => $module->get('category'),
                'version' => $version,
                'description' => trans('modules.history.updated', ['module' => $module->get('name')]),
            ]);
        }

        // Delete temp directory
        File::deleteDirectory($temp_path);

        return true;
    }

    public static function download($name, $alias, $version)
    {
        $file = null;
        $path = null;

        // Check core first
        $info = Info::all();

        if ($alias == 'core') {
            $url = 'core/download/' . $version . '/' . $info['php'] . '/' . $info['mysql'];
        } else {
            $url = 'apps/' . $alias . '/download/' . $version . '/' . $info['akaunting'] . '/' . $info['token'];
        }

        $response = static::getRemote($url, ['timeout' => 50, 'track_redirects' => true]);

        // Exception
        if ($response instanceof RequestException) {
            return [
                'success' => false,
                'errors' => trans('modules.errors.download', ['module' => $name]),
                'data' => [
                    'path' => $path
                ]
            ];
        }

        if ($response && ($response->getStatusCode() == 200)) {
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
                return [
                    'success' => false,
                    'errors' => trans('modules.errors.upload', ['module' => $name]),
                    'data' => [
                        'path' => $path
                    ]
                ];
            }

            $data = [
                'path' => $path
            ];

            return [
                'success' => true,
                'errors' => false,
                'data' => $data,
            ];
        }

        return [
            'success' => false,
            'errors' => trans('modules.errors.download', ['module' => $name]),
            'data' => [
                'path' => $path
            ]
        ];
    }

    public static function unzip($name, $path)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        $file = $temp_path . '/upload.zip';

        // Unzip the file
        $zip = new ZipArchive();

        if (($zip->open($file) !== true) || !$zip->extractTo($temp_path)) {
            return [
                'success' => false,
                'errors' => trans('modules.errors.unzip', ['module' => $name]),
                'data' => [
                    'path' => $path
                ]
            ];
        }

        $zip->close();

        // Delete zip file
        File::delete($file);

        return [
            'success' => true,
            'errors' => false,
            'data' => [
                'path' => $path
            ]
        ];
    }

    public static function fileCopy($name, $alias, $path, $version)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        if ($alias == 'core') {
            // Move all files/folders from temp path
            if (!File::copyDirectory($temp_path, base_path())) {
                return [
                    'success' => false,
                    'errors' => trans('modules.errors.file_copy', ['module' => $name]),
                    'data' => [
                        'path' => $path
                    ]
                ];
            }
        } else {
            // Get module instance
            $module = Module::findByAlias($alias);

            $module_path = module_path($module->get('name'));

            // Create module directory
            if (!File::isDirectory($module_path)) {
                File::makeDirectory($module_path);
            }

            // Move all files/folders from temp path
            if (!File::copyDirectory($temp_path, $module_path)) {
                return [
                    'success' => false,
                    'errors' => trans('modules.errors.file_copy', ['module' => $name]),
                    'data' => [
                        'path' => $path
                    ]
                ];
            }

            $model = Model::where('alias', $alias)->first();

            if (!empty($model)) {
                // Add history
                ModelHistory::create([
                    'company_id' => session('company_id'),
                    'module_id' => $model->id,
                    'category' => $module->get('category'),
                    'version' => $version,
                    'description' => trans('modules.history.updated', ['module' => $module->get('name')]),
                ]);
            }
        }

        // Delete temp directory
        File::deleteDirectory($temp_path);

        Artisan::call('cache:clear');

        return [
            'success' => true,
            'errors' => false,
            'data' => [
                'path' => $path
            ]
        ];
    }

    public static function migrate($name, $alias, $version, $installed)
    {
        // Check if the file mirror was successful
        if (($alias == 'core') && (version('short') != $version)) {
            return [
                'success' => false,
                'errors' => trans('modules.errors.migrate core', ['module' => $name]),
                'data' => []
            ];
        }

        // Clear cache after update
        Artisan::call('cache:clear');

        try {
            event(new UpdateFinished($alias, $installed, $version));

            return [
                'success' => true,
                'errors' => false,
                'data' => []
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => trans('modules.errors.migrate', ['module' => $name]),
                'data' => []
            ];
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

        $modules = Module::all();

        $versions = Versions::latest($modules);

        foreach ($versions as $alias => $version) {
            // Modules come as array
            if ($alias == 'core') {
                if (version_compare(version('short'), $version) != 0) {
                    $data['core'] = $version;
                }
            } else {
                $module = Module::findByAlias($alias);

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
