<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Category;

class IncomeByCategory extends Widget
{
    public $default_name = 'widgets.income_by_category';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public function show()
    {
        Category::with('income_transactions')->income()->each(function ($category) {
            $amount = 0;

            $this->applyFilters($category->income_transactions)->each(function ($transaction) use (&$amount) {
                $amount += $transaction->getAmountConvertedToDefault();
            });

            $this->addMoneyToDonut($category->color, $amount, $category->name);
        });

        $chart = $this->getDonutChart(trans_choice('general.incomes', 1), 0, 160, 6);

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}
