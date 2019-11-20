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
        // Get data from cache
        $items = Cache::get('apps.items');

        if (!empty($items)) {
            return $items;
        }

        $items = $this->remote('apps/items');

        Cache::put('apps.items', $items, Date::now()->addHour());

        return $items;
    }

    // Get Module
    public function getModule($alias)
    {
        // Get data from cache
        $item = Cache::get('apps.' . $alias);

        if (!empty($item)) {
            return $item;
        }

        $item = $this->remote('apps/' . $alias);

        Cache::put('apps.' . $alias, $item, Date::now()->addHour());

        return $item;
    }

    public function getDocumentation($alias)
    {
        // Get data from cache
        $documentation = Cache::get('apps.docs.' . $alias);

        if (!empty($documentation)) {
            return $documentation;
        }

        $documentation = $this->remote('apps/docs/' . $alias);

        Cache::put('apps.docs.' . $alias, $documentation, Date::now()->addHour());

        return $documentation;
    }

    public function getModuleReviews($alias, $data = [])
    {
        // Get data from cache
        $reviews = Cache::get('apps.' . $alias . '.reviews');

        if (!empty($reviews)) {
            return $reviews;
        }

        $reviews = $this->remote('apps/' . $alias . '/reviews', 'GET', $data);

        Cache::put('apps.' . $alias . '.reviews', $reviews, Date::now()->addHour());

        return $reviews;
    }

    public function getCategories()
    {
        // Get data from cache
        $categories = Cache::get('apps.categories');

        if (!empty($categories)) {
            return $categories;
        }

        $categories =  $this->remote('apps/categories');

        Cache::put('apps.categories', $categories, Date::now()->addHour());

        return $categories;
    }

    public function getModulesByCategory($alias, $data = [])
    {
        // Get data from cache
        $category = Cache::get('apps.categories.' . $alias);

        if (!empty($category)) {
            return $category;
        }

        $category = $this->remote('apps/categories/' . $alias, 'GET', $data);

        Cache::put('apps.categories.' . $alias, $category, Date::now()->addHour());

        return $category;
    }

    public function getVendors()
    {
        // Get data from cache
        $vendors = Cache::get('apps.vendors');

        if (!empty($vendors)) {
            return $vendors;
        }

        $vendors =  $this->remote('apps/vendors');

        Cache::put('apps.vendors', $vendors, Date::now()->addHour());

        return $vendors;
    }

    public function getModulesByVendor($alias, $data = [])
    {
        // Get data from cache
        $vendor = Cache::get('apps.vendors.' . $alias);

        if (!empty($vendor)) {
            return $vendor;
        }

        $vendor =  $this->remote('apps/vendors/' . $alias, 'GET', $data);

        Cache::put('apps.vendors.' . $alias, $vendor, Date::now()->addHour());

        return $vendor;
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
        // Get data from cache
        $pre_sale = Cache::get('apps.pre_sale');

        if (!empty($pre_sale)) {
            return $pre_sale;
        }

        $pre_sale = $this->remote('apps/pre_sale', 'GET', $data);

        Cache::put('apps.pre_sale', $pre_sale, Date::now()->addHour());

        return $pre_sale;
    }

    public function getPaidModules($data = [])
    {
        // Get data from cache
        $paid = Cache::get('apps.paid');

        if (!empty($paid)) {
            return $paid;
        }

        $paid = $this->remote('apps/paid', 'GET', $data);

        Cache::put('apps.paid', $paid, Date::now()->addHour());

        return $paid;
    }

    public function getNewModules($data = [])
    {
        // Get data from cache
        $new = Cache::get('apps.new');

        if (!empty($new)) {
            return $new;
        }

        $new = $this->remote('apps/new', 'GET', $data);

        Cache::put('apps.new', $new, Date::now()->addHour());

        return $new;
    }

    public function getFreeModules($data = [])
    {
        // Get data from cache
        $free = Cache::get('apps.free');

        if (!empty($free)) {
            return $free;
        }

        $free =  $this->remote('apps/free', 'GET', $data);

        Cache::put('apps.free', $free, Date::now()->addHour());

        return $free;
    }

    public function getFeaturedModules($data = [])
    {
        // Get data from cache
        $featured = Cache::get('apps.featured');

        if (!empty($featured)) {
            return $featured;
        }

        $featured = $this->remote('apps/featured', 'GET', $data);

        Cache::put('apps.featured', $featured, Date::now()->addHour());

        return $featured;
    }

    public function getSearchModules($data = [])
    {
        return $this->remote('apps/search', 'GET', $data);
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
                'error' => false,
                'message' => null,
                'data' => $data,
            ];
        }

        return [
            'success' => false,
            'error' => true,
            'message' => null,
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
                'error' => true,
                'message' => null,
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
            'error' => false,
            'message' => null,
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
            'redirect' => url("apps/post/" . $module->alias),
            'error' => false,
            'message' => null,
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
            'error' => false,
            'message' => null,
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
            'error' => false,
            'message' => null,
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
            'error' => false,
            'message' => null,
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
