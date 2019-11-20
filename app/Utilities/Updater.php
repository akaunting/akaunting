<?php

namespace App\Utilities;

use App\Models\Module\Module as Model;
use App\Models\Module\ModuleHistory as ModelHistory;
use App\Traits\SiteApi;
use Cache;
use Date;
use File;
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

    public static function download($alias, $version, $installed)
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

        $response = static::getRemote($url, 'GET', ['timeout' => 50, 'track_redirects' => true]);

        // Exception
        if ($response instanceof RequestException) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.download', ['module' => $alias]),
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
                    'error' => true,
                    'message' => trans('modules.errors.zip', ['module' => $alias]),
                    'data' => [
                        'path' => $path
                    ]
                ];
            }

            try {
                event(new \App\Events\Install\UpdateDownloaded($alias, $version, $installed));

                return [
                    'success' => true,
                    'error' => false,
                    'message' => null,
                    'data' => [
                        'path' => $path
                    ]
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'error' => true,
                    'message' => trans('modules.errors.download', ['module' => $alias]),
                    'data' => []
                ];
            }
        }

        return [
            'success' => false,
            'error' => true,
            'message' => trans('modules.errors.download', ['module' => $alias]),
            'data' => [
                'path' => $path
            ]
        ];
    }

    public static function unzip($path, $alias, $version, $installed)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        $file = $temp_path . '/upload.zip';

        // Unzip the file
        $zip = new ZipArchive();

        if (($zip->open($file) !== true) || !$zip->extractTo($temp_path)) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.unzip', ['module' => $alias]),
                'data' => [
                    'path' => $path
                ]
            ];
        }

        $zip->close();

        // Delete zip file
        File::delete($file);

        try {
            event(new \App\Events\Install\UpdateUnzipped($alias, $version, $installed));

            return [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [
                    'path' => $path
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.unzip', ['module' => $alias]),
                'data' => []
            ];
        }
    }

    public static function fileCopy($path, $alias, $version, $installed)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        if ($alias == 'core') {
            // Move all files/folders from temp path
            if (!File::copyDirectory($temp_path, base_path())) {
                return [
                    'success' => false,
                    'error' => true,
                    'message' => trans('modules.errors.file_copy', ['module' => $alias]),
                    'data' => [
                        'path' => $path
                    ]
                ];
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
                return [
                    'success' => false,
                    'error' => true,
                    'message' => trans('modules.errors.file_copy', ['module' => $alias]),
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
                    'category' => $module->get('category', 'payment-method'),
                    'version' => $version,
                    'description' => trans('modules.history.updated', ['module' => $module->get('alias')]),
                ]);
            }
        }

        // Delete temp directory
        File::deleteDirectory($temp_path);

        Artisan::call('cache:clear');

        try {
            event(new \App\Events\Install\UpdateCopied($alias, $version, $installed));

            return [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [
                    'path' => $path
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.file_copy', ['module' => $alias]),
                'data' => []
            ];
        }
    }

    public static function finish($alias, $version, $installed)
    {
        // Check if the file mirror was successful
        if (($alias == 'core') && (version('short') != $version)) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.file_copy', ['module' => $alias]),
                'data' => []
            ];
        }

        // Clear cache after update
        Artisan::call('cache:clear');

        try {
            event(new \App\Events\Install\UpdateFinished($alias, $installed, $version));

            return [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => []
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.finish', ['module' => $alias]),
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
