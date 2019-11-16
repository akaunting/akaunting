<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Banking\Transaction;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Utilities\Chartjs;
use Date;

class CashFlow extends AbstractWidget
{
    use Currencies, DateTime;

    // get any custom financial year beginning
    public $financial_start;

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'width' => 'col-md-12'
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $this->financial_start = $this->getFinancialStart()->format('Y-m-d');

        $cashflow = $this->getCashFlow();

        return view('widgets.cash_flow', [
            'config' => (object) $this->config,
            'cashflow' => $cashflow,
        ]);
    }

    private function getCashFlow()
    {
        // check and assign year start
        if (($year_start = Date::today()->startOfYear()->format('Y-m-d')) !== $this->financial_start) {
            $year_start = $this->financial_start;
        }

        $start = Date::parse(request('start', $year_start));
        $end = Date::parse(request('end', Date::parse($year_start)->addYear(1)->subDays(1)->format('Y-m-d')));
        $period = request('period', 'month');
        $range = request('range', 'custom');

        $start_month = $start->month;
        $end_month = $end->month;

        // Monthly
        $labels = array();

        $s = clone $start;

        if ($range == 'last_12_months') {
            $end_month   = 12;
            $start_month = 0;
        } elseif ($range == 'custom') {
            $end_month   = $end->diffInMonths($start);
            $start_month = 0;
        }

        for ($j = $end_month; $j >= $start_month; $j--) {
            $labels[$end_month - $j] = $s->format('M Y');

            if ($period == 'month') {
                $s->addMonth();
            } else {
                $s->addMonths(3);
                $j -= 2;
            }
        }

        $income = $this->calculateCashFlowTotals('income', $start, $end, $period);
        $expense = $this->calculateCashFlowTotals('expense', $start, $end, $period);
        $profit = $this->calculateCashFlowProfit($income, $expense);

        $options = [
            'tooltips' => [
                'backgroundColor' => '#f5f5f5',
                'titleFontColor' => '#333',
                'bodyFontColor' => '#666',
                'bodySpacing' => 4,
                'YrPadding' => 12,
                'mode' => 'nearest',
                'intersect' => 0,
                'position' => 'nearest'
            ],

              'responsive' => true,

              'scales' => [
                'yAxes' => [
                    [
                        'barPercentage' => 1.6,
                        'gridLines' => [
                        'drawBorder' => false,
                        'color' => 'rgba(29,140,248,0.1)',
                        'zeroLineColor' => 'transparent',
                        'borderDash' => [2],
                        'borderDashOffset' => [2],
                        ],
                        'ticks' => [
                        'padding' => 10,
                        'fontColor' => '#9e9e9e'
                        ]
                    ]
                ],

                'xAxes' => [
                    [
                        'barPercentage' => 1.6,
                        'gridLines' => [
                          'drawBorder' => false,
                          'color' => 'rgba(29,140,248,0.0)',
                          'zeroLineColor' => 'transparent'
                        ],
                        'ticks' => [
                          'suggestedMin' => 60,
                          'suggestedMax' => 125,
                          'padding' => 20,
                          'fontColor' => '#9e9e9e'
                        ]
                    ]
                ]
            ]
        ];

        $chart = new Chartjs();
        $chart->type('line')
            ->width(0)
            ->height(300)
            ->options($options)
            ->labels(array_values($labels));

        $chart->dataset(trans_choice('general.profits', 1), 'line', array_values($profit))
            ->backgroundColor('#6da252')
            ->color('#6da252')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
             ])
            ->fill(false);

        $chart->dataset(trans_choice('general.incomes', 1), 'line', array_values($income))
            ->backgroundColor('#328aef')
            ->color('#328aef')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
            ])
            ->fill(false);

        $chart->dataset(trans_choice('general.expenses', 1), 'line', array_values($expense))
            ->backgroundColor('#ef3232')
            ->color('#ef3232')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
            ])
            ->fill(false);

        return $chart;
    }

    private function calculateCashFlowTotals($type, $start, $end, $period)
    {
        $totals = array();

        $date_format = 'Y-m';

        if ($period == 'month') {
            $n = 1;
            $start_date = $start->format($date_format);
            $end_date = $end->format($date_format);
            $next_date = $start_date;
        } else {
            $n = 3;
            $start_date = $start->quarter;
            $end_date = $end->quarter;
            $next_date = $start_date;
        }

        $s = clone $start;

        //$totals[$start_date] = 0;
        while ($next_date <= $end_date) {
            $totals[$next_date] = 0;

            if ($period == 'month') {
                $next_date = $s->addMonths($n)->format($date_format);
            } else {
                if (isset($totals[4])) {
                    break;
                }

                $next_date = $s->addMonths($n)->quarter;
            }
        }

        $items = Transaction::type($type)->whereBetween('paid_at', [$start, $end])->isNotTransfer()->get();

        $this->setCashFlowTotals($totals, $items, $date_format, $period);

        return $totals;
    }

    private function setCashFlowTotals(&$totals, $items, $date_format, $period)
    {
        foreach ($items as $item) {
            if ($period == 'month') {
                $i = Date::parse($item->paid_at)->format($date_format);
            } else {
                $i = Date::parse($item->paid_at)->quarter;
            }

            if (!isset($totals[$i])) {
                continue;
            }

            $totals[$i] += $item->getAmountConvertedToDefault();
        }
    }

    private function calculateCashFlowProfit($incomes, $expenses)
    {
        $profit = [];

        foreach ($incomes as $key => $income) {
            if ($income > 0 && $income > $expenses[$key]) {
                $profit[$key] = $income - $expenses[$key];
            } else {
                $profit[$key] = 0;
            }
        }

        return $profit;
    }
}
