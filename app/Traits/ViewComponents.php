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
}
