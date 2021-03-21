<?php

namespace App\Abstracts\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

abstract class Document extends Component
{
    public function getTextFromConfig($type, $config_key, $default_key = '', $trans_type = 'trans')
    {
        $translation = '';

        // if set config translation config_key
        if ($translation = config('type.' . $type . '.translation.' . $config_key)) {
            return $translation;
        }

        $alias = config('type.' . $type . '.alias');
        $prefix = config('type.' . $type . '.translation.prefix');

        if (!empty($alias)) {
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

    public function getRouteFromConfig($type, $config_key, $config_parameters = [])
    {
        $route = '';

        // if set config trasnlation config_key
        if ($route = config('type.' . $type . '.route.' . $config_key)) {
            return $route;
        }

        $alias = config('type.' . $type . '.alias');
        $prefix = config('type.' . $type . '.route.prefix');

        // if use module set module alias
        if (!empty($alias)) {
            $route .= $alias . '.';
        }

        if (!empty($prefix)) {
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
        if ($permission = config('type.' . $type . '.permission.' . $config_key)) {
            return $permission;
        }

        $alias = config('type.' . $type . '.alias');
        $group = config('type.' . $type . '.group');
        $prefix = config('type.' . $type . '.permission.prefix');

        $permission = $config_key . '-';

        // if use module set module alias
        if (!empty($alias)) {
            $permission .= $alias . '-';
        }

        // if controller in folder it must
        if (!empty($group)) {
            $permission .= $group . '-';
        }

        $permission .= $prefix;

        return $permission;
    }

    public function getHideFromConfig($type, $config_key)
    {
        $hide = false;

        $hides = config('type.' . $type . '.hide');

        if (!empty($hides) && (in_array($config_key, $hides))) {
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
        if ($category_type = config('type.' . $type . '.category_type')) {
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
}
