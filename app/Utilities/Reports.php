<?php

namespace App\Utilities;

use App\Models\Common\Report;
use App\Models\Module\Module;
use Illuminate\Support\Str;

class Reports
{
    public static function getClasses($check_permission = true)
    {
        $classes = [];

        $list = [
            'App\Reports\IncomeSummary',
            'App\Reports\ExpenseSummary',
            'App\Reports\IncomeExpenseSummary',
            'App\Reports\TaxSummary',
            'App\Reports\ProfitLoss',
        ];

        Module::enabled()->each(function ($module) use (&$list) {
            $m = module($module->alias);

            if (!$m || empty($m->get('reports'))) {
                return;
            }

            $list = array_merge($list, (array) $m->get('reports'));
        });

        foreach ($list as $class) {
            if (!class_exists($class) || ($check_permission && !static::canRead($class))) {
                continue;
            }

            $classes[$class] = static::getDefaultName($class);
        }

        return $classes;
    }

    public static function getClassInstance($model, $load_data = true)
    {
        if (is_string($model)) {
            $model = Report::where('class', $model)->first();
        }

        if ((!$model instanceof Report) || !class_exists($model->class)) {
            return false;
        }

        $class = $model->class;

        return new $class($model, $load_data);
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
        if (strtolower($arr[0]) == 'modules') {
            $prefix .= Str::kebab($arr[1]) . '-';
        }

        $prefix .= 'reports-';

        $class_name = end($arr);

        $permission = $prefix . Str::kebab($class_name);

        return str_replace('--', '-', $permission);
    }

    public static function getDefaultName($class)
    {
        return (new $class())->getDefaultName();
    }
}
