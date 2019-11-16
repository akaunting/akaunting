<?php

namespace App\Traits;

use App\Traits\SiteApi;
use App\Utilities\Info;
use App\Models\Module\Module as Model;
use App\Models\Module\Module;
use Artisan;
use Cache;
use Date;
use File;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;
use ZipArchive;


trait Modules
{
    use SiteApi;

    public function checkToken($apiKey)
    {
        $data = [
            'form_params' => [
                'token' => $apiKey,
            ]
        ];

        $response = static::getRemote('token/check', 'POST', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            $result = json_decode($response->getBody());

            return ($result->success) ? true : false;
        }

        return false;
    }

    // Get All Modules
    public function getModules()
    {
        return $this->remote('apps/items');
    }

    // Get Module
    public function getModule($alias)
    {
        return $this->remote('apps/' . $alias);
    }

    public function getDocumentation($alias)
    {
        return $this->remote('apps/docs/' . $alias);
    }

    public function getModuleReviews($alias, $data = [])
    {
        return $this->remote('apps/' . $alias . '/reviews', 'GET', $data);
    }

    public function getCategories()
    {
        return $this->remote('apps/categories');
    }

    public function getModulesByCategory($alias, $data = [])
    {
        return $this->remote('apps/categories/' . $alias, 'GET', $data);
    }

    public function getVendors()
    {
        return $this->remote('apps/vendors');
    }

    public function getModulesByVendor($alias, $data = [])
    {
        return $this->remote('apps/vendors/' . $alias, 'GET', $data);
    }

    public function getMyModules($data = [])
    {
        return $this->remote('apps/my', 'GET', $data);
    }

    public function getInstalledModules($data = [])
    {
        $company_id = session('company_id');

        $cache = 'installed.' . $company_id . '.module';

        $installed = Cache::get($cache);

        if ($installed) {
            return $installed;
        }

        $installed = [];

        $modules = Module::all();
        $installed_modules = Model::where('company_id', '=', session('company_id'))->pluck('enabled', 'alias')->toArray();

        foreach ($modules as $module) {
            if (!array_key_exists($module->alias, $installed_modules)) {
                continue;
            }

            $result = $this->getModule($module->alias);

            if ($result) {
                $installed[] = $result;
            }
        }

        Cache::put($cache, $installed, Date::now()->addHour(6));

        return $installed;
    }

    public function getPreSaleModules($data = [])
    {
        return $this->remote('apps/pre_sale', 'GET', $data);
    }

    public function getPaidModules($data = [])
    {
        return $this->remote('apps/paid', 'GET', $data);
    }

    public function getNewModules($data = [])
    {
        return $this->remote('apps/new', 'GET', $data);
    }

    public function getFreeModules($data = [])
    {
        return $this->remote('apps/free', 'GET', $data);
    }

    public function getSearchModules($data = [])
    {
        return $this->remote('apps/search', 'GET', $data);
    }

    public function getFeaturedModules($data = [])
    {
        return $this->remote('apps/featured', 'GET', $data);
    }

    public function getCoreVersion()
    {
        $data['query'] = Info::all();

        $response = static::getRemote('core/version', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return $response->json();
        }

        return [];
    }

    public function downloadModule($path)
    {
        $response = static::getRemote($path);

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
                return false;
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
            'errors' => true,
            'data' => null,
        ];
    }

    public function unzipModule($path)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        $file = $temp_path . '/upload.zip';

        // Unzip the file
        $zip = new ZipArchive();

        if (!$zip->open($file) || !$zip->extractTo($temp_path)) {
            return [
                'success' => false,
                'errors' => true,
                'data' => null,
            ];
        }

        $zip->close();

        // Remove Zip
        File::delete($file);

        $data = [
            'path' => $path
        ];

        return [
            'success' => true,
            'errors' => false,
            'data' => $data,
        ];
    }

    public function installModule($path)
    {
        $temp_path = storage_path('app/temp') . '/' . $path;

        $modules_path = base_path() . '/modules';

        // Create modules directory
        if (!File::isDirectory($modules_path)) {
            File::makeDirectory($modules_path);
        }

        $module = json_decode(file_get_contents($temp_path . '/module.json'));

        $module_path = $modules_path . '/' . Str::studly($module->alias);

        // Create module directory
        if (!File::isDirectory($module_path)) {
            File::makeDirectory($module_path);
        }

        // Move all files/folders from temp path then delete it
        File::copyDirectory($temp_path, $module_path);
        File::deleteDirectory($temp_path);

        Artisan::call('cache:clear');

        $data = [
            'path' => $path,
            'name' => Str::studly($module->alias),
            'alias' => $module->alias
        ];

        return [
            'success' => true,
            'installed' => url("apps/post/" . $module->alias),
            'errors' => false,
            'data' => $data,
        ];
    }

    public function uninstallModule($alias)
    {
        $module = module($alias);

        $data = [
            'name' => $module->getName(),
            'category' => $module->get('category'),
            'version' => $module->get('version'),
        ];

        Artisan::call('cache:clear');

        $module->delete();

        // Cache Data clear
        File::deleteDirectory(storage_path('framework/cache/data'));

        return [
            'success' => true,
            'errors' => false,
            'data' => $data
        ];
    }

    public function enableModule($alias)
    {
        $module = module($alias);

        $data = [
            'name' => $module->getName(),
            'category' => $module->get('category'),
            'version' => $module->get('version'),
        ];

        $module->enable();

        Artisan::call('cache:clear');

        return [
            'success' => true,
            'errors' => false,
            'data' => $data
        ];
    }

    public function disableModule($alias)
    {
        $module = module($alias);

        $data = [
            'name' => $module->getName(),
            'category' => $module->get('category'),
            'version' => $module->get('version'),
        ];

        $module->disable();

        Artisan::call('cache:clear');

        return [
            'success' => true,
            'errors' => false,
            'data' => $data
        ];
    }

    public function moduleExists($alias)
    {
        $status = false;

        if (module($alias) instanceof \Akaunting\Module\Module) {
            $status = true;
        }

        return $status;
    }

    public function loadSuggestions()
    {
        // Get data from cache
        $data = Cache::get('suggestions');

        if (!empty($data)) {
            return $data;
        }

        $data = [];

        $url = 'apps/suggestions';

        $response = static::getRemote($url, 'GET', ['timeout' => 30, 'referer' => true]);

        // Exception
        if ($response instanceof RequestException) {
            return false;
        }

        // Bad response
        if (!$response || ($response->getStatusCode() != 200)) {
            return false;
        }

        $suggestions = json_decode($response->getBody())->data;

        if ($suggestions) {
            foreach ($suggestions as $suggestion) {
                $data[$suggestion->path] = $suggestion;
            }
        }

        Cache::put('suggestions', $data, Date::now()->addHour(6));

        return $data;
    }

    public function loadNotifications()
    {
        // Get data from cache
        $data = Cache::get('notifications');

        if (!empty($data)) {
            return $data;
        }

        $data = [];

        $url = 'apps/notifications';

        $response = static::getRemote($url, 'GET', ['timeout' => 30, 'referer' => true]);

        // Exception
        if ($response instanceof RequestException) {
            return false;
        }

        // Bad response
        if (!$response || ($response->getStatusCode() != 200)) {
            return false;
        }

        $notifications = json_decode($response->getBody())->data;

        foreach ($notifications as $notification) {
            $data[$notification->path][] = $notification;
        }

        Cache::put('notifications', $data, Date::now()->addHour(6));

        return $data;
    }

    public function getSuggestions($path)
    {
        // Get data from cache
        $data = Cache::get('suggestions');

        if (empty($data)) {
            $data = $this->loadSuggestions();
        }

        if (!empty($data) && array_key_exists($path, $data)) {
            return $data[$path];
        }

        return false;
    }

    public function getNotifications($path)
    {
        // Get data from cache
        $data = Cache::get('notifications');

        if (empty($data)) {
            $data = $this->loadNotifications();
        }

        if (!empty($data) && array_key_exists($path, $data)) {
            return $data[$path];
        }

        return false;
    }

    protected function remote($path, $method = 'GET', $data = [])
    {
        $response = static::getRemote($path, $method, $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }
}
