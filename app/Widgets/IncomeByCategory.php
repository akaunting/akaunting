<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Category;

class IncomeByCategory extends Widget
{
    public function show()
    {
        Category::with('income_transacions')->type('income')->enabled()->each(function ($category) {
            $amount = 0;

            $transactions = $this->applyFilters($category->income_transacions())->get();

            foreach ($transactions as $transacion) {
                $amount += $transacion->getAmountConvertedToDefault();
            }

            $this->addMoneyToDonut($category->color, $amount, $category->name);
        });

        $chart = $this->getDonutChart(trans_choice('general.incomes', 1), 0, 160, 6);

        return $this->view('widgets.income_by_category', [
            'chart' => $chart,
        ]);
    }

    public function getDefaultName()
    {
        return trans('widgets.income_by_category');
    }

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-6',
        ];
    }
}
