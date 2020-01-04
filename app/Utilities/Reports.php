<?php

namespace App\Utilities;

use App\Models\Common\Report;
use App\Models\Module\Module;

class Reports
{
    public static function getClasses()
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
            if (!class_exists($class)) {
                continue;
            }

            $classes[$class] = (new $class())->getDefaultName();
        }

        return $classes;
    }

    public static function getGroups()
    {
        return [
            'category' => trans_choice('general.categories', 1),
        ];
    }

    public static function getPeriods()
    {
        return [
            'monthly' => trans('general.monthly'),
            'quarterly' => trans('general.quarterly'),
            'yearly' => trans('general.yearly'),
        ];
    }

    public static function getBasises()
    {
        return [
            'accrual' => trans('general.accrual'),
            'cash' => trans('general.cash'),
        ];
    }

    public static function getCharts()
    {
        return [
            '0' => trans('general.disabled'),
            'line' => trans('reports.charts.line'),
        ];
    }

    public static function getClassInstance($model, $get_totals = true)
    {
        if (is_string($model)) {
            $model = Report::where('class', $model)->first();
        }

        if ((!$model instanceof Report) || !class_exists($model->class)) {
            return false;
        }

        $class = $model->class;

        return new $class($model, $get_totals);
    }
}
