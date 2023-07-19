<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Category;

class ExpensesByCategory extends Widget
{
    public $default_name = 'widgets.expenses_by_category';

    public $description = 'widgets.description.expenses_by_category';

    public $report_class = 'App\Reports\ExpenseSummary';

    public function show()
    {
        Category::with('expense_transactions')->expense()->withSubCategory()->getWithoutChildren()->each(function ($category) {
            $amount = 0;

            $this->applyFilters($category->expense_transactions)->each(function ($transaction) use (&$amount) {
                $amount += $transaction->getAmountConvertedToDefault();
            });

            $this->addMoneyToDonutChart($category->colorHexCode, $amount, $category->name);
        });

        $chart = $this->getDonutChart(trans_choice('general.expenses', 2), '100%', 300, 6);

        $chart->options['legend']['width'] = 160;
        $chart->options['legend']['position'] = 'right';

        return $this->view('widgets.donut_chart', [
            'chart' => $chart,
        ]);
    }
}
