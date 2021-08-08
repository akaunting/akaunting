<?php

namespace App\Traits;

use App\Models\Module\Module;
use App\Traits\SiteApi;
use App\Utilities\Info;
use Cache;
use Date;

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
    public function getModules($data = [])
    {
        $key = 'apps.app.' . $this->getDataKeyOfModules($data);

        $items = Cache::get($key);

        if (!empty($items)) {
            return $items;
        }

        $items = static::getResponseData('GET', 'apps/items');

        Cache::put($key, $items, Date::now()->addHour());

        return $items;
    }

    // Get Module
    public function getModule($alias)
    {
        $item = static::getResponseData('GET', 'apps/' . $alias);

        return $item;
    }

    public function getModuleDocumentation($alias, $data = [])
    {
        $key = 'apps.' . $alias . '.docs.' . $this->getDataKeyOfModules($data);

        $documentation = Cache::get($key);

        if (!empty($documentation)) {
            return $documentation;
        }

        $documentation = static::getResponseData('GET', 'apps/docs/' . $alias);

        Cache::put($key, $documentation, Date::now()->addHour());

        return $documentation;
    }

    public function getModuleReleases($alias, $data = [])
    {
        $key = 'apps.' . $alias . '.releases.' . $this->getDataKeyOfModules($data);

        $releases = Cache::get($key);

        if (!empty($releases)) {
            return $releases;
        }

        $releases = static::getResponseData('GET', 'apps/' . $alias . '/releases', $data);

        Cache::put($key, $releases, Date::now()->addHour());

        return $releases;
    }

    public function getModuleReviews($alias, $data = [])
    {
        $key = 'apps.' . $alias . '.reviews.' . $this->getDataKeyOfModules($data);

        $reviews = Cache::get($key);

        if (!empty($reviews)) {
            return $reviews;
        }

        $reviews = static::getResponseData('GET', 'apps/' . $alias . '/reviews', $data);

        Cache::put($key, $reviews, Date::now()->addHour());

        return $reviews;
    }

    public function getCategoriesOfModules($data = [])
    {
        $key = 'apps.categories.' . $this->getDataKeyOfModules($data);

        $categories = Cache::get($key);

        if (!empty($categories)) {
            return $categories;
        }

        $categories = static::getResponseData('GET', 'apps/categories');

        Cache::put($key, $categories, Date::now()->addHour());

        return $categories;
    }

    public function getModulesByCategory($alias, $data = [])
    {
        $key = 'apps.categories.' . $alias . '.' . $this->getDataKeyOfModules($data);

        $category = Cache::get($key);

        if (!empty($category)) {
            return $category;
        }

        $category = static::getResponseData('GET', 'apps/categories/' . $alias, $data);

        Cache::put($key, $category, Date::now()->addHour());

        return $category;
    }

    public function getVendorsOfModules($data = [])
    {
        $key = 'apps.vendors.' . $this->getDataKeyOfModules($data);

        $vendors = Cache::get($key);

        if (!empty($vendors)) {
            return $vendors;
        }

        $vendors = static::getResponseData('GET', 'apps/vendors');

        Cache::put($key, $vendors, Date::now()->addHour());

        return $vendors;
    }

    public function getModulesByVendor($alias, $data = [])
    {
        $key = 'apps.vendors.' . $alias . '.' . $this->getDataKeyOfModules($data);

        $vendor = Cache::get($key);

        if (!empty($vendor)) {
            return $vendor;
        }

        $vendor = static::getResponseData('GET', 'apps/vendors/' . $alias, $data);

        Cache::put($key, $vendor, Date::now()->addHour());

        return $vendor;
    }

    public function getMyModules($data = [])
    {
        return static::getResponseData('GET', 'apps/my', $data);
    }

    public function getInstalledModules()
    {
        $key = 'apps.installed.' . company_id();

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
        $key = 'apps.pre_sale.' . $this->getDataKeyOfModules($data);

        $pre_sale = Cache::get($key);

        if (!empty($pre_sale)) {
            return $pre_sale;
        }

        $pre_sale = static::getResponseData('GET', 'apps/pre_sale', $data);

        Cache::put($key, $pre_sale, Date::now()->addHour());

        return $pre_sale;
    }

    public function getPaidModules($data = [])
    {
        $key = 'apps.paid.' . $this->getDataKeyOfModules($data);

        $paid = Cache::get($key);

        if (!empty($paid)) {
            return $paid;
        }

        $paid = static::getResponseData('GET', 'apps/paid', $data);

        Cache::put($key, $paid, Date::now()->addHour());

        return $paid;
    }

    public function getNewModules($data = [])
    {
        $key = 'apps.new.' . $this->getDataKeyOfModules($data);

        $new = Cache::get($key);

        if (!empty($new)) {
            return $new;
        }

        $new = static::getResponseData('GET', 'apps/new', $data);

        Cache::put($key, $new, Date::now()->addHour());

        return $new;
    }

    public function getFreeModules($data = [])
    {
        $key = 'apps.free.' . $this->getDataKeyOfModules($data);

        $free = Cache::get($key);

        if (!empty($free)) {
            return $free;
        }

        $free = static::getResponseData('GET', 'apps/free', $data);

        Cache::put($key, $free, Date::now()->addHour());

        return $free;
    }

    public function getFeaturedModules($data = [])
    {
        $key = 'apps.featured.' . $this->getDataKeyOfModules($data);

        $featured = Cache::get($key);

        if (!empty($featured)) {
            return $featured;
        }

        $featured = static::getResponseData('GET', 'apps/featured', $data);

        Cache::put($key, $featured, Date::now()->addHour());

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

    public function moduleExists($alias)
    {
        if (!module($alias) instanceof \Akaunting\Module\Module) {
            return false;
        }

        return true;
    }

    public function moduleIsEnabled($alias)
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
        $key = 'apps.suggestions';

        $data = Cache::get($key);

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

        Cache::put($key, $data, Date::now()->addHour(6));

        return $data;
    }

    public function loadNotifications()
    {
        $key = 'apps.notifications';

        $data = Cache::get($key);

        if (!empty($data)) {
            return $data;
        }

        $data = [];

        if (!$notifications = static::getResponseData('GET', 'apps/notifications')) {
            return $data;
        }

        foreach ($notifications as $notification) {
            $data[$notification->path][] = $notification;
        }

        Cache::put($key, $data, Date::now()->addHour(6));

        return $data;
    }

    public function getSuggestions($path)
    {
        $key = 'apps.suggestions';

        $data = Cache::get($key);

        if (empty($data)) {
            $data = $this->loadSuggestions();
        }

        if (!empty($data) && array_key_exists($path, $data)) {
            return $data[$path];
        }

        return false;
    }

    public function getNotifications($path): array
    {
        $key = 'apps.notifications';

        $data = Cache::get($key);

        if (empty($data)) {
            $data = $this->loadNotifications();
        }

        if (!empty($data) && array_key_exists($path, $data)) {
            return (array) $data[$path];
        }

        return [];
    }

    public function getPageNumberOfModules($data = [])
    {
        if (empty($data['query']) || empty($data['query']['page'])) {
            return 1;
        }

        return $data['query']['page'];
    }

    public function getDataKeyOfModules($data = [])
    {
        $result = 'language.' . language()->getShortCode() . '.page.' . $this->getPageNumberOfModules($data);

        if (isset($data['query']['page'])) {
            unset($data['query']['page']);
        }

        if (isset($data['query'])){
            foreach($data['query'] as $key => $value) {
                $result .= '.' . $key . '.' . $value;
            }
        }

        return $result;
    }
}
