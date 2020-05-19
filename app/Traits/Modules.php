<?php

namespace App\Traits;

use App\Models\Module\Module;
use App\Traits\SiteApi;
use App\Utilities\Console;
use App\Utilities\Info;
use Artisan;
use Cache;
use Date;
use File;
use Illuminate\Support\Str;
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

        if (!$response = static::getResponse('POST', 'token/check', $data)) {
            return false;
        }

        $result = json_decode($response->getBody());

        return $result->success ? true : false;
    }

    // Get All Modules
    public function getModules()
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $items = Cache::get('apps.items.page.' . $page);

        if (!empty($items)) {
            return $items;
        }

        $items = static::getResponseData('GET', 'apps/items');

        Cache::put('apps.items.page.' . $page, $items, Date::now()->addHour());

        return $items;
    }

    // Get Module
    public function getModule($alias)
    {
        $item = static::getResponseData('GET', 'apps/' . $alias);

        return $item;
    }

    public function getDocumentation($alias)
    {
        // Get data from cache
        $documentation = Cache::get('apps.docs.' . $alias);

        if (!empty($documentation)) {
            return $documentation;
        }

        $documentation = static::getResponseData('GET', 'apps/docs/' . $alias);

        Cache::put('apps.docs.' . $alias, $documentation, Date::now()->addHour());

        return $documentation;
    }

    public function getModuleReviews($alias, $data = [])
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $reviews = Cache::get('apps.' . $alias . '.reviews.page.'. $page);

        if (!empty($reviews)) {
            return $reviews;
        }

        $reviews = static::getResponseData('GET', 'apps/' . $alias . '/reviews', $data);

        Cache::put('apps.' . $alias . '.reviews.page.' . $page, $reviews, Date::now()->addHour());

        return $reviews;
    }

    public function getCategories()
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $categories = Cache::get('apps.categories.page.' . $page);

        if (!empty($categories)) {
            return $categories;
        }

        $categories = static::getResponseData('GET', 'apps/categories');

        Cache::put('apps.categories.page.' . $page, $categories, Date::now()->addHour());

        return $categories;
    }

    public function getModulesByCategory($alias, $data = [])
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $category = Cache::get('apps.categories.' . $alias . '.page.' . $page);

        if (!empty($category)) {
            return $category;
        }

        $category = static::getResponseData('GET', 'apps/categories/' . $alias, $data);

        Cache::put('apps.categories.' . $alias . '.page.' . $page, $category, Date::now()->addHour());

        return $category;
    }

    public function getVendors()
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $vendors = Cache::get('apps.vendors.page.' . $page);

        if (!empty($vendors)) {
            return $vendors;
        }

        $vendors = static::getResponseData('GET', 'apps/vendors');

        Cache::put('apps.vendors.page.' . $page, $vendors, Date::now()->addHour());

        return $vendors;
    }

    public function getModulesByVendor($alias, $data = [])
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $vendor = Cache::get('apps.vendors.' . $alias . '.page.' . $page);

        if (!empty($vendor)) {
            return $vendor;
        }

        $vendor = static::getResponseData('GET', 'apps/vendors/' . $alias, $data);

        Cache::put('apps.vendors.' . $alias . '.page.' . $page, $vendor, Date::now()->addHour());

        return $vendor;
    }

    public function getMyModules($data = [])
    {
        return static::getResponseData('GET', 'apps/my', $data);
    }

    public function getInstalledModules($data = [])
    {
        $company_id = session('company_id');

        $key = 'installed.' . $company_id . '.module';

        if ($installed = Cache::get($key)) {
            return $installed;
        }

        $installed = [];

        Module::all()->each(function($module) use (&$installed) {
            if (!$this->moduleExists($module->alias)) {
                return;
            }

            if (!$result = $this->getModule($module->alias)) {
                return;
            }

            $installed[] = $result;
        });

        Cache::put($key, $installed, Date::now()->addHour(6));

        return $installed;
    }

    public function getPreSaleModules($data = [])
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $pre_sale = Cache::get('apps.pre_sale.page.' . $page);

        if (!empty($pre_sale)) {
            return $pre_sale;
        }

        $pre_sale = static::getResponseData('GET', 'apps/pre_sale', $data);

        Cache::put('apps.pre_sale.page.' . $page, $pre_sale, Date::now()->addHour());

        return $pre_sale;
    }

    public function getPaidModules($data = [])
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $paid = Cache::get('apps.paid.page.' . $page);

        if (!empty($paid)) {
            return $paid;
        }

        $paid = static::getResponseData('GET', 'apps/paid', $data);

        Cache::put('apps.paid.page.' . $page, $paid, Date::now()->addHour());

        return $paid;
    }

    public function getNewModules($data = [])
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $new = Cache::get('apps.new.page.' . $page);

        if (!empty($new)) {
            return $new;
        }

        $new = static::getResponseData('GET', 'apps/new', $data);

        Cache::put('apps.new.page.' . $page, $new, Date::now()->addHour());

        return $new;
    }

    public function getFreeModules($data = [])
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $free = Cache::get('apps.free.page.' . $page);

        if (!empty($free)) {
            return $free;
        }

        $free = static::getResponseData('GET', 'apps/free', $data);

        Cache::put('apps.free.page.' . $page, $free, Date::now()->addHour());

        return $free;
    }

    public function getFeaturedModules($data = [])
    {
        $page = isset($data['query']['page']) ? $data['query']['page'] : 1;

        // Get data from cache
        $featured = Cache::get('apps.featured.page.' . $page);

        if (!empty($featured)) {
            return $featured;
        }

        $featured = static::getResponseData('GET', 'apps/featured', $data);

        Cache::put('apps.featured.page.' . $page, $featured, Date::now()->addHour());

        return $featured;
    }

    public function getSearchModules($data = [])
    {
        return static::getResponseData('GET', 'apps/search', $data);
    }

    public function getCoreVersion()
    {
        $data['query'] = Info::all();

        if (!$response = static::getResponse('GET', 'core/version', $data)) {
            return [];
        }

        return $response->json();
    }

    public function downloadModule($path)
    {
        if (empty($path)) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.download', ['module' => '']),
                'data' => null,
            ];
        }

        if (!$response = static::getResponse('GET', $path)) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.download', ['module' => '']),
                'data' => null,
            ];
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
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.download', ['module' => '']),
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'error' => false,
            'message' => null,
            'data' => [
                'path' => $path,
            ],
        ];
    }

    public function unzipModule($path)
    {
        if (empty($path)) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.unzip', ['module' => '']),
                'data' => null,
            ];
        }

        $temp_path = storage_path('app/temp') . '/' . $path;

        $file = $temp_path . '/upload.zip';

        // Unzip the file
        $zip = new ZipArchive();

        if (!$zip->open($file) || !$zip->extractTo($temp_path)) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.unzip', ['module' => '']),
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
        if (empty($path)) {
            return [
                'success' => false,
                'error' => true,
                'message' => trans('modules.errors.finish', ['module' => '']),
                'data' => null,
            ];
        }

        $temp_path = storage_path('app/temp') . '/' . $path;

        $modules_path = config('module.paths.modules');

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

        $data = [
            'path' => $path,
            'name' => Str::studly($module->alias),
            'alias' => $module->alias,
        ];

        $company_id = session('company_id');
        $locale = app()->getLocale();

        Cache::forget('installed.' . $company_id . '.module');

        $command = "module:install {$module->alias} {$company_id} {$locale}";

        if (true !== $result = Console::run($command)) {
            $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $module->alias]);

            return [
                'success' => false,
                'error' => true,
                'message' => $message,
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'redirect' => route('apps.app.show', $module->alias),
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
            'data' => $data,
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
            'data' => $data,
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
            'data' => $data,
        ];
    }

    public function moduleExists($alias)
    {
        if (!module($alias) instanceof \Akaunting\Module\Module) {
            return false;
        }

        return true;
    }

    public function moduleEnabled($alias)
    {
        if (!$this->moduleExists($alias)) {
            return false;
        }

        if (!Module::alias($alias)->enabled()->first()) {
            return false;
        }

        return true;
    }

    public function loadSuggestions()
    {
        // Get data from cache
        $data = Cache::get('suggestions');

        if (!empty($data)) {
            return $data;
        }

        $data = [];

        if (!$suggestions = static::getResponseData('GET', 'apps/suggestions')) {
            return $data;
        }

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

        if (!$notifications = static::getResponse('GET', 'apps/notifications')) {
            return $data;
        }

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
}
