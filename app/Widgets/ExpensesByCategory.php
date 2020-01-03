<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Category;

class ExpensesByCategory extends Widget
{
    public function show()
    {
        Category::with('expense_transactions')->type('expense')->enabled()->each(function ($category) {
            $amount = 0;

            $transactions = $this->applyFilters($category->expense_transactions())->get();

            foreach ($transactions as $transacion) {
                $amount += $transacion->getAmountConvertedToDefault();
            }

            $this->addMoneyToDonut($category->color, $amount, $category->name);
        });

        $chart = $this->getDonutChart(trans_choice('general.expenses', 2), 0, 160, 6);

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }

    public function getDefaultName()
    {
        return trans('widgets.expenses_by_category');
    }

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-6',
        ];
    }
}
