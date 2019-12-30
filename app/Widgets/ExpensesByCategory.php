<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Category;

class ExpensesByCategory extends Widget
{
    protected $config = [
        'width' => 'col-md-6',
    ];

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

        return view('widgets.expenses_by_category', [
            'config' => (object) $this->config,
            'chart' => $chart,
        ]);
    }
}
