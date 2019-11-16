<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Setting\Category;
use App\Utilities\Chartjs;
use Date;

class ExpensesByCategory extends AbstractWidget
{
    public $expense_donut = ['colors' => [], 'labels' => [], 'values' => []];

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'width' => 'col-md-6'
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $expenses_amount = $open_bill = $overdue_bill = 0;

        // Get categories
        $categories = Category::with(['bills', 'payments'])->type(['expense'])->enabled()->get();

        foreach ($categories as $category) {
            $amount = 0;

            // Payments
            foreach ($category->payments as $payment) {
                $amount += $payment->getAmountConvertedToDefault();
            }

            $expenses_amount += $amount;

            // Bills
            $bills = $category->bills()->accrued()->get();
            foreach ($bills as $bill) {
                list($open, $overdue) = $this->calculateInvoiceBillTotals($bill, 'bill');

                $open_bill += $open;
                $overdue_bill += $overdue;
            }

            $this->addToExpenseDonut($category->color, $amount, $category->name);
        }

        // Show donut prorated if there is no expense
        if (array_sum($this->expense_donut['values']) == 0) {
            foreach ($this->expense_donut['values'] as $key => $value) {
                $this->expense_donut['values'][$key] = 1;
            }
        }

        // Get 6 categories by amount
        $colors = $labels = [];
        $values = collect($this->expense_donut['values'])->sort()->reverse()->take(6)->all();
        foreach ($values as $id => $val) {
            $colors[$id] = $this->expense_donut['colors'][$id];
            $labels[$id] = $this->expense_donut['labels'][$id];
        }

        $options = [
            'color' => $colors,

            'cutoutPercentage' => 80,

            'legend' => [
                'position' => 'right'
            ],

            'tooltips' => [
                'backgroundColor' => '#f5f5f5',
                'titleFontColor' => '#333',
                'bodyFontColor' => '#666',
                'bodySpacing' => 4,
                'xPadding' => 12,
                'mode' => 'nearest',
                'intersect' => 0,
                'position' => 'nearest'
            ],

            'scales' => [

            'yAxes' => [
                [
                'display' => 0,

                'ticks' => [
                    'display' => false
                    ],

                'gridLines' => [
                    'drawBorder' => false,
                    'zeroLineColor' => 'transparent',
                    'color' => 'rgba(255,255,255,0.05)'
                    ]
                ]
            ],

            'xAxes' => [
                [
                'display' => 0,

                'barPercentage' => 1.6,

                'gridLines' => [
                    'drawBorder' => false,
                    'color' => 'rgba(255,255,255,0.1)',
                    'zeroLineColor' => 'transparent'
                    ],

                'ticks' => [
                    'display' => false
                    ]
                ]
            ]
        ]
    ];

        $donut_expenses = new Chartjs();

        $donut_expenses->type('doughnut')
            ->width(0)
            ->height(160)
            ->options($options)
            ->labels(array_values($labels));

        $donut_expenses->dataset(trans_choice('general.expenses', 2), 'doughnut', array_values($values))
        ->backgroundColor(array_values($colors));

        return view('widgets.expenses_by_category', [
            'config' => (object) $this->config,
            'donut_expenses' => $donut_expenses,
        ]);
    }

    public function getData()
    {
        //
        $expenses_amount = $open_bill = $overdue_bill = 0;

        // Get categories
        $categories = Category::with(['bills', 'payments'])->type(['expense'])->enabled()->get();

        foreach ($categories as $category) {
            $amount = 0;

            // Payments
            foreach ($category->payments as $payment) {
                $amount += $payment->getAmountConvertedToDefault();
            }

            $expenses_amount += $amount;

            // Bills
            $bills = $category->bills()->accrued()->get();
            foreach ($bills as $bill) {
                list($open, $overdue) = $this->calculateInvoiceBillTotals($bill, 'bill');

                $open_bill += $open;
                $overdue_bill += $overdue;
            }

            $this->addToExpenseDonut($category->color, $amount, $category->name);
        }

        // Show donut prorated if there is no expense
        if (array_sum($this->expense_donut['values']) == 0) {
            foreach ($this->expense_donut['values'] as $key => $value) {
                $this->expense_donut['values'][$key] = 1;
            }
        }

        // Get 6 categories by amount
        $colors = $labels = [];
        $values = collect($this->expense_donut['values'])->sort()->reverse()->take(6)->all();

        foreach ($values as $id => $val) {
            $colors[$id] = $this->expense_donut['colors'][$id];
            $labels[$id] = $this->expense_donut['labels'][$id];
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

    private function addToExpenseDonut($color, $amount, $text)
    {
        $this->expense_donut['colors'][] = $color;
        $this->expense_donut['labels'][] = money($amount, setting('default.currency'), true)->format() . ' - ' . $text;
        $this->expense_donut['values'][] = (int) $amount;
    }
}
