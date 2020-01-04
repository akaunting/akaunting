<?php

namespace App\Utilities;

use App\Models\Common\Widget;
use App\Models\Module\Module;

class Widgets
{
    public static function getClasses()
    {
        $classes = [];

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
        ];

        Module::enabled()->each(function ($module) use (&$list) {
            $m = module($module->alias);

            if (!$m || empty($m->get('widgets'))) {
                return;
            }

            $list = array_merge($list, (array) $m->get('widgets'));
        });

        foreach ($list as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $name = (new $class())->getDefaultName();

            $classes[$class] = $name;
        }

        return $classes;
    }

    public static function getInstance($model)
    {
        if (is_string($model)) {
            $model = Widget::where('class', $model)->first();
        }

        if ((!$model instanceof Widget) || !class_exists($model->class)) {
            return false;
        }

        $class = $model->class;

        return new $class($model);
    }

    public static function show($model, ...$arguments)
    {
        if (!$class = static::getInstance($model)) {
            return '';
        }

        return $class->show(...$arguments);
    }
}
