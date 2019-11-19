<?php

namespace App\Utilities;

use App\Models\Module\Module;

class Reports
{
    public static function getClasses()
    {
        $classes = [];

        $core_classes = [
            'App\Reports\IncomeSummary',
            'App\Reports\ExpenseSummary',
            'App\Reports\IncomeExpenseSummary',
            'App\Reports\TaxSummary',
            'App\Reports\ProfitLoss',
        ];

        static::parseClasses($classes, $core_classes);

        $modules = Module::enabled()->get();

        foreach ($modules as $module) {
            $m = module($module->alias);

            // Check if the module exists and has reports
            if (!$m || empty($m->get('reports'))) {
                continue;
            }

            static::parseClasses($classes, $m->get('reports'));
        }

        return $classes;
    }

    protected static function parseClasses(&$classes, $list)
    {
        foreach ($list as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $name = (new $class())->getName();

            $classes[$class] = $name;
        }
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

    public static function getClassInstance($report, $get_totals = true)
    {
        return (new $report->class($report, $get_totals));
    }
}
