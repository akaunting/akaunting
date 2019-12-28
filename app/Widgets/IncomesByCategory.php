<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Setting\Category;
use App\Utilities\Chartjs;
use Date;

class IncomesByCategory extends AbstractWidget
{
    public $donut = ['colors' => [], 'labels' => [], 'values' => []];

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'width' => 'col-md-6',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $incomes_amount = $open_invoice = $overdue_invoice = 0;

        // Get categories
        $categories = Category::with(['invoices', 'income_transacions'])->type(['income'])->enabled()->get();

        foreach ($categories as $category) {
            $amount = 0;

            // Transactions
            foreach ($category->income_transacions as $transacion) {
                $amount += $transacion->getAmountConvertedToDefault();
            }

            $incomes_amount += $amount;

            // Invoices
            $invoices = $category->invoices()->accrued()->get();
            foreach ($invoices as $invoice) {
                list($open, $overdue) = $this->calculateInvoiceBillTotals($invoice, 'invoice');

                $open_invoice += $open;
                $overdue_invoice += $overdue;
            }

            $this->addToDonut($category->color, $amount, $category->name);
        }

        // Show donut prorated if there is no income
        if (array_sum($this->donut['values']) == 0) {
            foreach ($this->donut['values'] as $key => $value) {
                $this->donut['values'][$key] = 1;
            }
        }

        // Get 6 categories by amount
        $colors = $labels = [];
        $values = collect($this->donut['values'])->sort()->reverse()->take(6)->all();

        foreach ($values as $id => $val) {
            $colors[$id] = $this->donut['colors'][$id];
            $labels[$id] = $this->donut['labels'][$id];
        }

        $chart = new Chartjs();

        $chart->type('doughnut')
            ->width(0)
            ->height(160)
            ->options($this->getChartOptions($colors))
            ->labels(array_values($labels));

        $chart->dataset(trans_choice('general.incomes', 2), 'doughnut', array_values($values))
        ->backgroundColor(array_values($colors));

        return view('widgets.incomes_by_category', [
            'config' => (object) $this->config,
            'chart' => $chart,
        ]);
    }

    public function getData()
    {
        //
        $incomes_amount = $open_invoice = $overdue_invoice = 0;

        // Get categories
        $categories = Category::with(['invoices', 'income_transacions'])->type(['income'])->enabled()->get();

        foreach ($categories as $category) {
            $amount = 0;

            // Transactions
            foreach ($category->income_transacions as $transacion) {
                $amount += $transacion->getAmountConvertedToDefault();
            }

            $incomes_amount += $amount;

            // Invoices
            $invoices = $category->invoices()->accrued()->get();
            foreach ($invoices as $invoice) {
                list($open, $overdue) = $this->calculateInvoiceBillTotals($invoice, 'invoice');

                $open_invoice += $open;
                $overdue_invoice += $overdue;
            }

            $this->addToDonut($category->color, $amount, $category->name);
        }

        // Show donut prorated if there is no income
        if (array_sum($this->donut['values']) == 0) {
            foreach ($this->donut['values'] as $key => $value) {
                $this->donut['values'][$key] = 1;
            }
        }

        // Get 6 categories by amount
        $colors = $labels = [];
        $values = collect($this->donut['values'])->sort()->reverse()->take(6)->all();

        foreach ($values as $id => $val) {
            $colors[$id] = $this->donut['colors'][$id];
            $labels[$id] = $this->donut['labels'][$id];
        }

        return [
            'labels' => $labels,
            'colors' => $colors,
            'values' => $values,
        ];
    }

    private function calculateInvoiceBillTotals($item, $type)
    {
        $open = $overdue = 0;

        $today = Date::today()->toDateString();

        $code_field = $type . '_status_code';

        if ($item->$code_field != 'paid') {
            $payments = 0;

            if ($item->$code_field == 'partial') {
                foreach ($item->transactions as $transaction) {
                    $payments += $transaction->getAmountConvertedToDefault();
                }
            }

            // Check if it's open or overdue invoice
            if ($item->due_at > $today) {
                $open += $item->getAmountConvertedToDefault() - $payments;
            } else {
                $overdue += $item->getAmountConvertedToDefault() - $payments;
            }
        }

        return [$open, $overdue];
    }

    private function addToDonut($color, $amount, $text)
    {
        $this->donut['colors'][] = $color;
        $this->donut['labels'][] = money($amount, setting('default.currency'), true)->format() . ' - ' . $text;
        $this->donut['values'][] = (int) $amount;
    }

    private function getChartOptions($colors)
    {
        return [
            'backgroudColor' => array_values($colors),
            'cutoutPercentage' => 80,
            'legend' => [
                'position' => 'right',
            ],
            'tooltips' => [
                'backgroundColor' => '#f5f5f5',
                'titleFontColor' => '#333',
                'bodyFontColor' => '#666',
                'bodySpacing' => 4,
                'xPadding' => 12,
                'mode' => 'nearest',
                'intersect' => 0,
                'position' => 'nearest',
            ],
            'scales' => [
                'yAxes' => [
                    'display' => 0,
                    'ticks' => [
                        'display' => false,
                    ],
                    'gridLines' => [
                        'drawBorder' => false,
                        'zeroLineColor' => 'transparent',
                        'color' => 'rgba(255,255,255,0.05)',
                    ],
                ],
                'xAxes' => [
                    'display' => 0,
                    'barPercentage' => 1.6,
                    'ticks' => [
                        'display' => false,
                    ],
                    'gridLines' => [
                        'drawBorder' => false,
                        'color' => 'rgba(255,255,255,0.1)',
                        'zeroLineColor' => 'transparent',
                    ],
                ],
            ],
        ];
    }
}
