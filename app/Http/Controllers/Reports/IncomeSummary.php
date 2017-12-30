<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\Revenue;
use App\Models\Setting\Category;
use Charts;
use Date;

class IncomeSummary extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dates = $totals = $incomes = $incomes_graph = $categories = [];

        $status = request('status');

        //if ($filter != 'upcoming') {
            $categories = Category::enabled()->type('income')->pluck('name', 'id')->toArray();
        //}

        // Add Invoice in Categories
        $categories[0] = trans_choice('general.invoices', 2);

        // Get year
        $year = request('year');
        if (empty($year)) {
            $year = Date::now()->year;
        }

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $dates[$j] = Date::parse($year . '-' . $j)->format('F');

            $incomes_graph[Date::parse($year . '-' . $j)->format('F-Y')] = 0;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            );

            // Invoice
            $incomes[0][$dates[$j]] = array(
                'category_id' => 0,
                'name' => trans_choice('general.invoices', 1),
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            );

            foreach ($categories as $category_id => $category_name) {
                $incomes[$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1
                );
            }
        }

        // Invoices
        switch ($status) {
            case 'paid':
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'paid_at');
                break;
            case 'upcoming':
                $invoices = Invoice::accrued()->monthsOfYear('due_at')->get();
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'due_at');
                break;
            default:
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'invoiced_at');
                break;
        }

        // Revenues
        if ($status != 'upcoming') {
            $revenues = Revenue::monthsOfYear('paid_at')->get();
            $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
        }

        // Incomes chart
        $chart = Charts::multi('line', 'chartjs')
            ->dimensions(0, 300)
            ->colors(['#00c0ef'])
            ->dataset(trans_choice('general.incomes', 1), $incomes_graph)
            ->labels($dates)
            ->credits(false)
            ->view('vendor.consoletvs.charts.chartjs.multi.line');

        return view('reports.income_summary.index', compact('chart', 'dates', 'categories', 'incomes', 'totals'));
    }

    private function setAmount(&$graph, &$totals, &$incomes, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            $date = Date::parse($item->$date_field)->format('F');

            if ($type == 'invoice') {
                $category_id = 0;
            } else {
                $category_id = $item->category_id;
            }

            if (!isset($incomes[$category_id])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if (($type == 'invoice') && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $incomes[$category_id][$date]['amount'] += $amount;
            $incomes[$category_id][$date]['currency_code'] = $item->currency_code;
            $incomes[$category_id][$date]['currency_rate'] = $item->currency_rate;

            $graph[Date::parse($item->$date_field)->format('F-Y')] += $amount;

            $totals[$date]['amount'] += $amount;
        }
    }
}
