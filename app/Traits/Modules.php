<?php

namespace App\Traits;

use App\Utilities\Info;
use App\Models\Module\Module as Model;
use Artisan;
use Cache;
use Date;
use File;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Module;
use ZipArchive;

trait Modules
{

    public function checkToken($token)
    {
        $data = [
            'form_params' => [
                'token' => $token,
            ]
        ];

        $response = $this->getRemote('token/check', 'POST', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            $result = json_decode($response->getBody());

            return ($result->success) ? true : false;
        }

        return false;
    }

    public function getModules()
    {
        $response = $this->getRemote('apps/items');

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getModule($alias)
    {
        $response = $this->getRemote('apps/' . $alias);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getDocumentation($alias)
    {
        $response = $this->getRemote('apps/docs/' . $alias);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getModuleReviews($alias, $data = [])
    {
        $response = $this->getRemote('apps/' . $alias . '/reviews', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getCategories()
    {
        $response = $this->getRemote('apps/categories');

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getModulesByCategory($alias, $data = [])
    {
        $response = $this->getRemote('apps/categories/' . $alias, 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getVendors()
    {
        $response = $this->getRemote('apps/vendors');

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getModulesByVendor($alias, $data = [])
    {
        $response = $this->getRemote('apps/vendors/' . $alias, 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getMyModules($data = [])
    {
        $response = $this->getRemote('apps/my', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
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
        $installed_modules = Model::where('company_id', '=', session('company_id'))->pluck('status', 'alias')->toArray();

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
        $response = $this->getRemote('apps/pre_sale', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getPaidModules($data = [])
    {
        $response = $this->getRemote('apps/paid', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getNewModules($data = [])
    {
        $response = $this->getRemote('apps/new', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getFreeModules($data = [])
    {
        $response = $this->getRemote('apps/free', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getSearchModules($data = [])
    {
        $response = $this->getRemote('apps/search', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getFeaturedModules($data = [])
    {
        $response = $this->getRemote('apps/featured', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return json_decode($response->getBody())->data;
        }

        return [];
    }

    public function getCoreVersion()
    {
        $data['query'] = Info::all();

        $response = $this->getRemote('core/version', 'GET', $data);

        if ($response && ($response->getStatusCode() == 200)) {
            return $response->json();
        }

        return [];
    }

    public function downloadModule($path)
    {
        $response = $this->getRemote($path);

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

        $module_path = $modules_path . '/' . $module->name;

        // Create module directory
        if (!File::isDirectory($module_path)) {
            File::makeDirectory($module_path);
        }

        // Move all files/folders from temp path then delete it
        File::copyDirectory($temp_path, $module_path);
        File::deleteDirectory($temp_path);

        Artisan::call('cache:clear');

        $data = [
            'path'  => $path,
            'name' => $module->name,
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
        $module = Module::findByAlias($alias);

        $data = [
            'name' => $module->get('name'),
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
            'data'   => $data
        ];
    }

    public function enableModule($alias)
    {
        $module = Module::findByAlias($alias);

        $data = [
            'name' => $module->get('name'),
            'category' => $module->get('category'),
            'version' => $module->get('version'),
        ];

        $module->enable();

        Artisan::call('cache:clear');

        return [
            'success' => true,
            'errors' => false,
            'data'   => $data
        ];
    }

    public function disableModule($alias)
    {
        $module = Module::findByAlias($alias);

        $data = [
          'name' => $module->get('name'),
          'category' => $module->get('category'),
          'version' => $module->get('version'),
        ];

        $module->disable();

        Artisan::call('cache:clear');

        return [
            'success' => true,
            'errors' => false,
            'data'   => $data
        ];
    }

    public function moduleExists($alias)
    {
        $status = false;

        if (Module::findByAlias($alias) instanceof \Nwidart\Modules\Module) {
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

        $response = $this->getRemote($url, 'GET', ['timeout' => 30, 'referer' => true]);

        // Exception
        if ($response instanceof RequestException) {
            return false;
        }

        // Bad response
        if (!$response || ($response->getStatusCode() != 200)) {
            return false;
        }

        $suggestions = json_decode($response->getBody())->data;

        foreach ($suggestions as $suggestion) {
            $data[$suggestion->path] = $suggestion;
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

        $response = $this->getRemote($url, 'GET', ['timeout' => 30, 'referer' => true]);

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

    protected function getRemote($path, $method = 'GET', $data = array())
    {
        $base = 'https://akaunting.com/api/';

        $client = new Client(['verify' => false, 'base_uri' => $base]);

        $headers['headers'] = [
            'Authorization' => 'Bearer ' . setting('general.api_token'),
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
            $result = false;
        }

        return $result;
    }
}
