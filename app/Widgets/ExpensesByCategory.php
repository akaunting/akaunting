<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Category;

class ExpensesByCategory extends Widget
{
    public $default_name = 'widgets.expenses_by_category';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public function show()
    {
        Category::with('expense_transactions')->expense()->each(function ($category) {
            $amount = 0;

            $this->applyFilters($category->expense_transactions)->each(function ($transaction) use (&$amount) {
                $amount += $transaction->getAmountConvertedToDefault();
            });

            $this->addMoneyToDonut($category->color, $amount, $category->name);
        });

        $chart = $this->getDonutChart(trans_choice('general.expenses', 2), 0, 160, 6);

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}
