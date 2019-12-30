<?php

namespace App\Utilities;

use App\Models\Module\Module;

class Widgets
{
    public static function getClasses()
    {
        $classes = [];

        $core_classes = [
            'App\Widgets\TotalIncome',
            'App\Widgets\TotalExpenses',
            'App\Widgets\TotalProfit',
            'App\Widgets\CashFlow',
            'App\Widgets\IncomeByCategory',
            'App\Widgets\ExpensesByCategory',
            'App\Widgets\AccountBalance',
            'App\Widgets\LatestIncome',
            'App\Widgets\LatestExpenses',
        ];

        static::parseClasses($classes, $core_classes);

        $modules = Module::enabled()->get();

        foreach ($modules as $module) {
            $m = module($module->alias);

            // Check if the module exists and has widgets
            if (!$m || empty($m->get('widgets'))) {
                continue;
            }

            static::parseClasses($classes, $m->get('widgets'));
        }

        return $classes;
    }

    protected static function parseClasses(&$classes, $list)
    {
        foreach ($list as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $name = (new $class())->getDefaultName();

            $classes[$class] = $name;
        }
    }

    public static function getInstance($model)
    {
        $class = $model->class;

        return new $class($model);
    }

    public static function show($model)
    {
        return static::getInstance($model)->show();
    }
}
