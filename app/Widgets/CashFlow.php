<?php

namespace App\Widgets;

use Akaunting\Apexcharts\Chart;
use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use App\Traits\Charts;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Utilities\Date;

class CashFlow extends Widget
{
    use Charts, Currencies, DateTime;

    public $default_name = 'widgets.cash_flow';

    public $default_settings = [
        'width' => '100',
    ];

    public $description = 'widgets.description.cash_flow';

    public $report_class = 'Modules\CashFlowStatement\Reports\CashFlowStatement';

    public $start_date;

    public $end_date;

    public $period;

    public function show()
    {
        $this->setFilter();

        $income = array_values($this->calculateTotals('income'));
        $expense = array_values($this->calculateTotals('expense'));
        $profit = array_values($this->calculateProfit($income, $expense));

        $chart = new Chart();

        $chart->setType('line')
            ->setDefaultLocale($this->getDefaultLocaleOfChart())
            ->setLocales($this->getLocaleTranslationOfChart())
            ->setStacked(true)
            ->setBar(['columnWidth' => '40%'])
            ->setLegendPosition('top')
            ->setYaxisLabels(['formatter' => $this->getChartLabelFormatter()])
            ->setLabels(array_values($this->getLabels()))
            ->setColors($this->getColors())
            ->setDataset(trans('general.incoming'), 'column', $income)
            ->setDataset(trans('general.outgoing'), 'column', $expense)
            ->setDataset(trans_choice('general.profits', 1), 'line', $profit);

        $incoming_amount = money(array_sum($income));
        $outgoing_amount = money(abs(array_sum($expense)));
        $profit_amount = money(array_sum($profit));

        $totals = [
            'incoming_exact'        => $incoming_amount->format(),
            'incoming_for_humans'   => $incoming_amount->formatForHumans(),
            'outgoing_exact'        => $outgoing_amount->format(),
            'outgoing_for_humans'   => $outgoing_amount->formatForHumans(),
            'profit_exact'          => $profit_amount->format(),
            'profit_for_humans'     => $profit_amount->formatForHumans(),
        ];

        return $this->view('widgets.cash_flow', [
            'chart' => $chart,
            'totals' => $totals,
        ]);
    }

    public function setFilter(): void
    {
        $financial_year = $this->getFinancialYear();

        $this->start_date = Date::parse(request('start_date', $financial_year->copy()->getStartDate()->toDateString()))->startOfDay();
        $this->end_date = Date::parse(request('end_date', $financial_year->copy()->getEndDate()->toDateString()))->endOfDay();
        $this->period = request('period', 'month');
    }

    public function getLabels(): array
    {
        $labels = [];

        $start_date = $this->start_date->copy();

        $counter = $this->end_date->diffInMonths($this->start_date);

        for ($j = 0; $j <= $counter; $j++) {
            $labels[$j] = $start_date->format($this->getMonthlyDateFormat());

            if ($this->period == 'month') {
                $start_date->addMonth();
            } else {
                $start_date->addMonths(3);
                $j += 2;
            }
        }

        return $labels;
    }

    public function getColors(): array
    {
        return [
            '#8bb475',
            '#fb7185',
            '#7779A2',
        ];
    }

    private function calculateTotals($type): array
    {
        $totals = [];

        $date_format = 'Y-m';

        if ($this->period == 'month') {
            $n = 1;
            $start_date = $this->start_date->format($date_format);
            $end_date = $this->end_date->format($date_format);
            $next_date = $start_date;
        } else {
            $n = 3;
            $start_date = $this->start_date->quarter;
            $end_date = $this->end_date->quarter;
            $next_date = $start_date;
        }

        $s = clone $this->start_date;

        //$totals[$start_date] = 0;
        while ($next_date <= $end_date) {
            $totals[$next_date] = 0;

            if ($this->period == 'month') {
                $next_date = $s->addMonths($n)->format($date_format);
            } else {
                if (isset($totals[4])) {
                    break;
                }

                $next_date = $s->addMonths($n)->quarter;
            }
        }

        $items = $this->applyFilters(Transaction::$type()->whereBetween('paid_at', [$this->start_date, $this->end_date])->isNotTransfer())->get();

        $this->setTotals($totals, $items, $date_format);

        return $totals;
    }

    private function setTotals(&$totals, $items, $date_format): void
    {
        $type = 'income';

        foreach ($items as $item) {
            $type = $item->type;

            if ($this->period == 'month') {
                $i = Date::parse($item->paid_at)->format($date_format);
            } else {
                $i = Date::parse($item->paid_at)->quarter;
            }

            if (!isset($totals[$i])) {
                continue;
            }

            $totals[$i] += $item->getAmountConvertedToDefault();
        }

        $precision = currency()->getPrecision();

        foreach ($totals as $key => $value) {
            if ($type == 'expense') {
                $value = -1 * $value;
            }

            $totals[$key] = round($value, $precision);
        }
    }

    private function calculateProfit($incomes, $expenses): array
    {
        $profit = [];

        $precision = currency()->getPrecision();

        foreach ($incomes as $key => $income) {
            $value = $income - abs($expenses[$key]);

            $profit[$key] = round($value, $precision);
        }

        return $profit;
    }
}
