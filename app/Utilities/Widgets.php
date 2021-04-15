<?php

namespace App\Utilities;

use App\Models\Common\Widget;
use App\Models\Module\Module;
use Illuminate\Support\Str;

class Widgets
{
    public static function getClasses($alias = 'core', $check_permission = true)
    {
        $classes = $list = [];

        if (in_array($alias, ['core', 'all'])) {
            $list = [
                'App\Widgets\TotalIncome',
                'App\Widgets\TotalExpenses',
                'App\Widgets\TotalProfit',
                'App\Widgets\CashFlow',
                'App\Widgets\IncomeByCategory',
                'App\Widgets\ExpensesByCategory',
                'App\Widgets\AccountBalance',
                'App\Widgets\LatestIncome',
                'App\Widgets\LatestExpenses',
                'App\Widgets\Currencies',
            ];
        }

        Module::enabled()->each(function ($module) use (&$list, $alias) {
            if (!in_array($alias, [$module->alias, 'all'])) {
                return;
            }

            $m = module($module->alias);

            if (!$m || empty($m->get('widgets'))) {
                return;
            }

            $list = array_merge($list, (array) $m->get('widgets'));
        });

        foreach ($list as $class) {
            if (!class_exists($class) || ($check_permission && !static::canRead($class))) {
                continue;
            }

            $classes[$class] = static::getDefaultName($class);
        }

        return $classes;
    }

    public static function getClassInstance($model)
    {
        if (is_string($model)) {
            $class_name = $model;

            if (!class_exists($class_name)) {
                return false;
            }

            $model = Widget::where('dashboard_id', session('dashboard_id'))->where('class', $class_name)->first();

            if (!$model instanceof Widget) {
                $class = (new $class_name());

                $model = new Widget();
                $model->id = 0;
                $model->company_id = company_id();
                $model->dashboard_id = session('dashboard_id');
                $model->class = $class_name;
                $model->name = $class->getDefaultName();
                $model->sort = 99;
                $model->settings = $class->getDefaultSettings();
            }
        } else {
            if ((!$model instanceof Widget) || !class_exists($model->class)) {
                return false;
            }

            $class_name = $model->class;
        }

        return new $class_name($model);
    }

    public static function show($model, ...$arguments)
    {
        if (!$class = static::getClassInstance($model)) {
            return '';
        }

        return $class->show(...$arguments);
    }

    public static function canShow($class)
    {
        return (static::isModuleEnabled($class) && static::canRead($class));
    }

    public static function canRead($class)
    {
        return user()->can(static::getPermission($class));
    }

    public static function getPermission($class)
    {
        $arr = explode('\\', $class);

        $prefix = 'read-';

        // Add module
        if ($alias = static::getModuleAlias($arr)) {
            $prefix .= $alias . '-';
        }

        $prefix .= 'widgets-';

        $class_name = end($arr);

        $permission = $prefix . Str::kebab($class_name);

        return str_replace('--', '-', $permission);
    }

    public static function getDefaultName($class)
    {
        return (new $class())->getDefaultName();
    }

    public static function isModuleEnabled($class)
    {
        if (!$alias = static::getModuleAlias($class)) {
            return true;
        }

        if (Module::alias($alias)->enabled()->first()) {
            return true;
        }

        return false;
    }

    public static function isModule($class)
    {
        $arr = is_array($class) ? $class : explode('\\', $class);

        return (strtolower($arr[0]) == 'modules');
    }

    public static function getModuleAlias($class)
    {
        if (!static::isModule($class)) {
            return false;
        }

        $arr = is_array($class) ? $class : explode('\\', $class);

        return Str::kebab($arr[1]);
    }
}
