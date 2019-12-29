<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Category;

class IncomeByCategory extends Widget
{
    protected $config = [
        'width' => 'col-md-6',
    ];

    public function show()
    {
        Category::with(['income_transacions'])->type(['income'])->enabled()->each(function ($category) {
            $amount = 0;

            foreach ($category->income_transacions as $transacion) {
                $amount += $transacion->getAmountConvertedToDefault();
            }

            $this->addMoneyToDonut($category->color, $amount, $category->name);
        });

        $chart = $this->getDonutChart(trans_choice('general.incomes', 1), 0, 160, 6);

        return view('widgets.income_by_category', [
            'config' => (object) $this->config,
            'chart' => $chart,
        ]);
    }
}
