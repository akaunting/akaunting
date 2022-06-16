<?php

namespace App\Traits;

use Akaunting\Module\Module;
use App\Events\Common\BulkActionsAdding;
use App\Traits\Modules;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait ViewComponents
{
    use Modules;

    public function setParentData()
    {
        $excludes = [
            'componentName',
            'attributes',
            'except',
        ];

        foreach ($this as $key => $value) {
            if (in_array($key, $excludes)) {
                continue;
            }

            $this->{$key} = $this->getParentData($key, $value);
        }
    }

    public function getTextFromConfig($type, $config_key, $default_key = '', $trans_type = 'trans')
    {
        $translation = '';

        // if set config translation config_key
        if ($translation = config('type.' . static::OBJECT_TYPE . '.' . $type . '.translation.' . $config_key)) {
            return $translation;
        }

        $alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias');
        $prefix = config('type.' . static::OBJECT_TYPE . '.' . $type . '.translation.prefix');

        if (! empty($alias)) {
            $alias .= '::';
        }

        // This magic trans key..
        $translations = [
            'general' => $alias . 'general.' . $default_key,
            'prefix' => $alias . $prefix . '.' . $default_key,
            'config_general' => $alias . 'general.' . $config_key,
            'config_prefix' => $alias . $prefix . '.' . $config_key,
        ];

        switch ($trans_type) {
            case 'trans':
                foreach ($translations as $trans) {
                    if (trans($trans) !== $trans) {
                        return $trans;
                    }
                }

                break;
            case 'trans_choice':
                foreach ($translations as $trans_choice) {
                    if (trans_choice($trans_choice, 1) !== $trans_choice) {
                        return $trans_choice;
                    }
                }

                break;
        }

        return $translation;
    }

    public function getRouteFromConfig($type, $config_key, $config_parameters = [], $modal = false)
    {
        $route = '';

        // if set config trasnlation config_key
        if ($route = config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.' . $config_key)) {
            return $route;
        }

        $alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias');
        $prefix = config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix');

        // if use module set module alias
        if (! empty($alias)) {
            $route .= $alias . '.';
        }

        if ($modal == true) {
            $route .= 'modals.';
        }

        if (! empty($prefix)) {
            $route .= $prefix . '.';
        }

        $route .= $config_key;

        try {
            route($route, $config_parameters);
        } catch (\Exception $e) {
            try {
                $route = Str::plural($type, 2) . '.' . $config_key;

                route($route, $config_parameters);
            } catch (\Exception $e) {
                $route = '';
            }
        }

        return $route;
    }

    public function getPermissionFromConfig($type, $config_key)
    {
        $permission = '';

        // if set config trasnlation config_key
        if ($permission = config('type.' . static::OBJECT_TYPE . '.' . $type . '.permission.' . $config_key)) {
            return $permission;
        }

        $alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias');
        $group = config('type.' . static::OBJECT_TYPE . '.' . $type . '.group');
        $prefix = config('type.' . static::OBJECT_TYPE . '.' . $type . '.permission.prefix');

        $permission = $config_key . '-';

        // if use module set module alias
        if (! empty($alias)) {
            $permission .= $alias . '-';
        }

        // if controller in folder it must
        if (! empty($group)) {
            $permission .= $group . '-';
        }

        $permission .= $prefix;

        return $permission;
    }

    public function getHideFromConfig($type, $config_key)
    {
        $hide = false;

        $hides = config('type.' . static::OBJECT_TYPE . '.' . $type . '.hide');

        if (! empty($hides) && (in_array($config_key, $hides))) {
            $hide = true;
        }

        return $hide;
    }

    public function getClassFromConfig($type, $config_key)
    {
        $class_key = 'type.' . $type . '.class.' . $config_key;

        return config($class_key, '');
    }

    public function getCategoryFromConfig($type)
    {
        $category_type = '';

        // if set config trasnlation config_key
        if ($category_type = config('type.' . static::OBJECT_TYPE . '.' . $type . '.category_type')) {
            return $category_type;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $category_type = 'expense';
                break;
            case 'item':
                $category_type = 'item';
                break;
            case 'other':
                $category_type = 'other';
                break;
            case 'transfer':
                $category_type = 'transfer';
                break;
            default:
                $category_type = 'income';
                break;
        }

        return $category_type;
    }

    public function getScriptFromConfig($type, $config_key)
    {
        $script_key = config('type.' . static::OBJECT_TYPE . '.' . $type . '.script.' . $config_key, '');

        return $script_key;
    }

    protected function getTextPage($type, $textPage)
    {
        if (! empty($textPage)) {
            return $textPage;
        }

        $config_route_prefix = config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix', static::DEFAULT_PLURAL_TYPE);

        $page = str_replace('-', '_', $config_route_prefix);

        $translation = $this->getTextFromConfig($type, 'page', $page);

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.' . $page;
    }

    protected function getGroup($type, $group)
    {
        if (! empty($group)) {
            return $group;
        }

        return config('type.' . static::OBJECT_TYPE . '.' . $type . '.group', static::DEFAULT_PLURAL_TYPE);
    }

    protected function getPage($type, $page)
    {
        if (! empty($page)) {
            return $page;
        }

        return Str::plural($type);
    }

    protected function getPermissionCreate($type, $permissionCreate)
    {
        if (! empty($permissionCreate)) {
            return $permissionCreate;
        }

        $permissionCreate = $this->getPermissionFromConfig($type, 'create');

        return $permissionCreate;
    }

    protected function getPermissionUpdate($type, $permissionUpdate)
    {
        if (! empty($permissionUpdate)) {
            return $permissionUpdate;
        }

        $permissionUpdate = $this->getPermissionFromConfig($type, 'update');

        return $permissionUpdate;
    }

    protected function getPermissionDelete($type, $permissionDelete)
    {
        if (! empty($permissionDelete)) {
            return $permissionDelete;
        }

        $permissionDelete = $this->getPermissionFromConfig($type, 'delete');

        return $permissionDelete;
    }

    protected function getIndexRoute($type, $indexRoute)
    {
        if (! empty($indexRoute)) {
            return $indexRoute;
        }

        $route = $this->getRouteFromConfig($type, 'index');

        if (!empty($route)) {
            return $route;
        }

        return static::DEFAULT_PLURAL_TYPE . '.index';
    }

    protected function getShowRoute($type, $showRoute)
    {
        if (! empty($showRoute)) {
            return $showRoute;
        }

        $route = $this->getRouteFromConfig($type, 'show', 1);

        if (!empty($route)) {
            return $route;
        }

        return static::DEFAULT_PLURAL_TYPE . '.show';
    }

    protected function getCreateRoute($type, $createRoute)
    {
        if (! empty($createRoute)) {
            return $createRoute;
        }

        $route = $this->getRouteFromConfig($type, 'create');

        if (! empty($route)) {
            return $route;
        }

        return static::DEFAULT_PLURAL_TYPE . '.create';
    }

    protected function getEditRoute($type, $editRoute)
    {
        if (! empty($editRoute)) {
            return $editRoute;
        }

        $route = $this->getRouteFromConfig($type, 'edit', 1);

        if (! empty($route)) {
            return $route;
        }

        return static::DEFAULT_PLURAL_TYPE . '.edit';
    }

    protected function getDuplicateRoute($type, $duplicateRoute)
    {
        if (! empty($duplicateRoute)) {
            return $duplicateRoute;
        }

        $route = $this->getRouteFromConfig($type, 'duplicate', 1);

        if (! empty($route)) {
            return $route;
        }

        return static::DEFAULT_PLURAL_TYPE . '.duplicate';
    }

    protected function getDeleteRoute($type, $deleteRoute)
    {
        if (! empty($deleteRoute)) {
            return $deleteRoute;
        }

        $route = $this->getRouteFromConfig($type, 'destroy', 1);

        if (! empty($route)) {
            return $route;
        }

        return static::DEFAULT_PLURAL_TYPE . '.destroy';
    }

    protected function getCancelRoute($type, $cancelRoute)
    {
        if (! empty($cancelRoute)) {
            return $cancelRoute;
        }

        $route = $this->getRouteFromConfig($type, 'index');

        if (! empty($route)) {
            return $route;
        }

        return static::DEFAULT_PLURAL_TYPE . '.index';
    }

    protected function getImportRoute($importRoute)
    {
        if (! empty($importRoute)) {
            return $importRoute;
        }

        $route = 'import.create';

        return $route;
    }

    protected function getImportRouteParameters($type, $importRouteParameters)
    {
        if (! empty($importRouteParameters)) {
            return $importRouteParameters;
        }

        $alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias');
        $group = config('type.' . static::OBJECT_TYPE . '.' . $type . '.group');
        $prefix = config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix');

        if (empty($group) && ! empty($alias)){
            $group = $alias;
        } else if (empty($group) && empty($alias)) {
            $group = 'sales';
        }

        $importRouteParameters = [
            'group' => $group,
            'type' => $prefix,
        ];

        return $importRouteParameters;
    }

    protected function getExportRoute($type, $exportRoute)
    {
        if (! empty($exportRoute)) {
            return $exportRoute;
        }

        $route = $this->getRouteFromConfig($type, 'export');

        if (! empty($route)) {
            return $route;
        }

        return static::DEFAULT_PLURAL_TYPE . '.export';
    }

    protected function getSearchStringModel($type, $searchStringModel)
    {
        if (! empty($searchStringModel)) {
            return $searchStringModel;
        }

        $search_string_model = config('type.' . static::OBJECT_TYPE . '.' . $type . '.search_string_model');

        if (! empty($search_string_model)) {
            return $search_string_model;
        }

        if ($group = config('type.' . static::OBJECT_TYPE . '.' . $type . '.group')) {
            $group = Str::studly(Str::singular($group)) . '\\';
        }

        $prefix = Str::studly(Str::singular(config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix')));

        if ($alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias')) {
            $searchStringModel = 'Modules\\' . Str::studly($alias) .'\Models\\' . $group . $prefix;
        } else {
            $searchStringModel = 'App\Models\\' . $group . $prefix;
        }

        return $searchStringModel;
    }

    protected function getBulkActionClass($type, $bulkActionClass)
    {
        if (! empty($bulkActionClass)) {
            return $bulkActionClass;
        }

        $bulk_actions = config('type.' . static::OBJECT_TYPE . '.' . $type . '.bulk_actions');

        if (! empty($bulk_actions)) {
            return $bulk_actions;
        }

        $file_name = '';

        if ($group = config('type.' . static::OBJECT_TYPE . '.' . $type . '.group')) {
            $file_name .= Str::studly($group) . '\\';
        }

        if ($prefix = config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix')) {
            $file_name .= Str::studly($prefix);
        }

        if ($alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias')) {
            $module = module($alias);

            if (! $module instanceof Module) {
                $b = new \stdClass();
                $b->actions = [];

                event(new BulkActionsAdding($b));

                return $b->actions;
            }

            $bulkActionClass = 'Modules\\' . $module->getStudlyName() . '\BulkActions\\' . $file_name;
        } else {
            $bulkActionClass = 'App\BulkActions\\' .  $file_name;
        }

        return $bulkActionClass;
    }

    protected function getBulkActionRouteParameters($type, $bulkActionRouteParameters)
    {
        if (! empty($bulkActionRouteParameters)) {
            return $bulkActionRouteParameters;
        }

        $group = config('type.' . static::OBJECT_TYPE . '.' . $type . '.group');

        if (! empty(config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias'))) {
            $group = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias');
        }

        $bulkActionRouteParameters = [
            'group' => $group,
            'type' => config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix')
        ];

        return $bulkActionRouteParameters;
    }

    protected function getClassBulkAction($type, $classBulkAction)
    {
        if (! empty($classBulkAction)) {
            return $classBulkAction;
        }

        $class = $this->getClassFromConfig($type, 'bulk_action');

        if (! empty($class)) {
            return $class;
        }

        return 'ltr:pr-6 rtl:pl-6 hidden sm:table-cell';
    }

    protected function getImageEmptyPage($type, $imageEmptyPage)
    {
        if (! empty($imageEmptyPage)) {
            return $imageEmptyPage;
        }

        $image_empty_page = config('type.' . static::OBJECT_TYPE . '.' . $type . '.image_empty_page');

        if (! empty($image_empty_page)) {
            return $image_empty_page;
        }

        $page = str_replace('-', '_', config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix', 'invoices'));
        $image_path = 'public/img/empty_pages/' . $page . '.png';

        if ($alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias')) {
            $image_path = 'modules/' . Str::studly($alias) . '/Resources/assets/img/empty_pages/' . $page . '.png';
        }

        return $image_path;
    }

    protected function getTextEmptyPage($type, $textEmptyPage)
    {
        if (! empty($textEmptyPage)) {
            return $textEmptyPage;
        }

        $page = str_replace('-', '_', config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix', 'invoices'));

        $translation = $this->getTextFromConfig($type, 'empty_page', 'empty.' . $page);

        if (! empty($translation)) {
            return $translation;
        }

        return 'general.empty.' . $page;
    }

    protected function getTextSectionTitle($type, $key, $default_key = '')
    {
        $translation = $this->getTextFromConfig($type, 'section_'. $key . '_title', $key);

        if (! empty($translation)) {
            return $translation;
        }

        if ($default_key) {
            return $default_key;
        }

        return 'general.' . $key;
    }

    protected function getTextSectionDescription($type, $key, $default_key = '')
    {
        $translation = $this->getTextFromConfig($type, 'section_'. $key . '_description', 'form_description.' . $key);

        if (! empty($translation)) {
            return $translation;
        }

        if ($default_key) {
            return $default_key;
        }

        return 'general.form_description.' . $key;
    }

    protected function getUrlDocsPath($type, $urlDocsPath)
    {
        if (! empty($urlDocsPath)) {
            return $urlDocsPath;
        }

        $docs_path = config('type.' . static::OBJECT_TYPE . '.' . $type . '.docs_path');

        if (! empty($docs_path)) {
            return $docs_path;
        }

        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $docsPath = 'purchases/bills';
                break;
            case 'vendor':
                $docsPath = 'purchases/vendors';
                break;
            case 'customer':
                $docsPath = 'sales/customers';
                break;
            case 'transaction':
                $docsPath = 'banking/transactions';
                break;
            default:
                $docsPath = 'sales/invoices';
                break;
        }

        return 'https://akaunting.com/docs/user-manual/' . $docsPath;
    }

    public function getSuggestionModule()
    {
        return !empty($this->suggestions) ? Arr::random($this->suggestions) : false;
    }

    public function getSuggestionModules()
    {
        if ((! $user = user()) || $user->cannot('read-modules-home')) {
            return [];
        }

        if (! $path = Route::current()->uri()) {
            return [];
        }

        $path = str_replace('{company_id}/', '', $path);

        if (! $suggestions = $this->getSuggestions($path)) {
            return [];
        }

        $modules = [];

        foreach ($suggestions->modules as $s_module) {
            if ($this->moduleIsEnabled($s_module->alias)) {
                continue;
            }

            $s_module->action_url = company_id() . '/' . $s_module->action_url;

            $modules[] = $s_module;
        }

        if (empty($modules)) {
            return [];
        }

        return $modules;
    }

    protected function getFormRoute($type, $formRoute, $model = false)
    {
        if (! empty($formRoute)) {
            return $formRoute;
        }

        $prefix = 'store';
        $parameters = [];

        if (! empty($model)) {
            $prefix = 'update';
            $parameters = [$model->id];
        }

        $route = $this->getRouteFromConfig($type, $prefix, $parameters);

        return (! empty($model)) ? [$route, $model->id] : $route;
    }

    protected function getFormMethod($type, $formMethod, $model = false)
    {
        if (! empty($formMethod)) {
            return $formMethod;
        }

        $method = 'POST';

        if (! empty($model)) {
            $method = 'PATCH';
        }

        return $method;
    }

    protected function getAlias($type, $alias)
    {
        if (!empty($alias)) {
            return $alias;
        }

        if ($alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias')) {
            return $alias;
        }

        return 'core';
    }

    protected function getScriptFolder($type, $folder)
    {
        if (!empty($folder)) {
            return $folder;
        }

        if ($folder = config('type.' . static::OBJECT_TYPE . '.' . $type . '.script.folder')) {
            return $folder;
        }

        return '';
    }

    protected function getScriptFile($type, $file)
    {
        if (!empty($file)) {
            return $file;
        }

        if ($file = config('type.' . static::OBJECT_TYPE . '.' . $type . '.script.file')) {
            return $file;
        }

        return '';
    }

    protected function convertClasstoHex($class)
    {
        $colors = [
            'gray' => '#6b7280',
            'gray-50' => '#f9fafb',
            'gray-100' => '#f3f4f6',
            'gray-200' => '#e5e7eb',
            'gray-300' => '#d1d5db',
            'gray-400' => '#9ca3af',
            'gray-500' => '#6b7280',
            'gray-600' => '#4b5563',
            'gray-700' => '#374151',
            'gray-800' => '#1f2937',
            'gray-900' => '#111827',

            'red' => '#cc0000',
            'red-50' => '#fcf2f2',
            'red-100' => '#fae6e6',
            'red-200' => '#f2bfbf',
            'red-300' => '#eb9999',
            'red-400' => '#db4d4d',
            'red-500' => '#cc0000',
            'red-600' => '#b80000',
            'red-700' => '#990000',
            'red-800' => '#7a0000',
            'red-900' => '#640000',

            'yellow' => '#eab308',
            'yellow-50' => '#fefce8',
            'yellow-100' => '#fef9c3',
            'yellow-200' => '#fef08a',
            'yellow-300' => '#fde047',
            'yellow-400' => '#facc15',
            'yellow-500' => '#eab308',
            'yellow-600' => '#ca8a04',
            'yellow-700' => '#a16207',
            'yellow-800' => '#854d0e',
            'yellow-900' => '#713f12',

            'green' => '#6ea152',
            'green-50' => '#f8faf6',
            'green-100' => '#f1f6ee',
            'green-200' => '#dbe8d4',
            'green-300' => '#c5d9ba',
            'green-400' => '#9abd86',
            'green-500' => '#6ea152',
            'green-600' => '#63914a',
            'green-700' => '#53793e',
            'green-800' => '#426131',
            'green-900' => '#364f28',

            'blue' => '#006ea6',
            'blue-50' => '#f2f8fb',
            'blue-100' => '#e6f1f6',
            'blue-200' => '#bfdbe9',
            'blue-300' => '#99c5db',
            'blue-400' => '#4d9ac1',
            'blue-500' => '#006ea6',
            'blue-600' => '#006395',
            'blue-700' => '#00537d',
            'blue-800' => '#004264',
            'blue-900' => '#003651',

            'indigo' => '#6366f1',
            'indigo-50' => '#eef2ff',
            'indigo-100' => '#e0e7ff',
            'indigo-200' => '#c7d2fe',
            'indigo-300' => '#a5b4fc',
            'indigo-400' => '#818cf8',
            'indigo-500' => '#6366f1',
            'indigo-600' => '#4f46e5',
            'indigo-700' => '#4338ca',
            'indigo-800' => '#3730a3',
            'indigo-900' => '#312e81',

            'purple' => '#55588b',
            'purple-50' => '#f7f7f9',
            'purple-100' => '#eeeef3',
            'purple-200' => '#d5d5e2',
            'purple-300' => '#bbbcd1',
            'purple-400' => '#888aae',
            'purple-500' => '#55588b',
            'purple-600' => '#4d4f7d',
            'purple-700' => '#404268',
            'purple-800' => '#333553',
            'purple-900' => '#2a2b44',

            'pink' => '#ec4899',
            'pink-50' => '#fdf2f8',
            'pink-100' => '#fce7f3',
            'pink-200' => '#fbcfe8',
            'pink-300' => '#f9a8d4',
            'pink-400' => '#f472b6',
            'pink-500' => '#ec4899',
            'pink-600' => '#db2777',
            'pink-700' => '#be185d',
            'pink-800' => '#9d174d',
            'pink-900' => '#831843',
        ];

        if (Arr::exists($colors, $class)) {
            return $colors[$class];
        }

        return $class;
    }
}
