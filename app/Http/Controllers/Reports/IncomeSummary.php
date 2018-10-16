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

        $categories = Category::enabled()->type('income')->pluck('name', 'id')->toArray();

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
                $this->setRecurring($invoices, 'paid_at');
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'paid_at');
                break;
            case 'upcoming':
                $invoices = Invoice::accrued()->monthsOfYear('due_at')->get();
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'due_at');
                $this->setRecurring($invoices, 'due_at');
                break;
            default:
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setRecurring($invoices, 'invoiced_at');
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'invoiced_at');
                break;
        }

        // Revenues
        if ($status != 'upcoming') {
            $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
            $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
        }

        // Check if it's a print or normal request
        if (request('print')) {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line_print';
            $view_template = 'reports.income_summary.print';
        } else {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line';
            $view_template = 'reports.income_summary.index';
        }

        // Incomes chart
        $chart = Charts::multi('line', 'chartjs')
            ->dimensions(0, 300)
            ->colors(['#00c0ef'])
            ->dataset(trans_choice('general.incomes', 1), $incomes_graph)
            ->labels($dates)
            ->credits(false)
            ->view($chart_template);

        return view($view_template, compact('chart', 'dates', 'categories', 'incomes', 'totals'));
    }

    private function setRecurring(&$invoices, $date)
    {
        foreach ($invoices as $invoice) {
            if ($invoice['table'] == 'invoice_payments') {
                $item  = $invoice->invoice;
                $item->category_id = $invoice->category_id;

                $invoice = $item;
            }

            if (!empty($invoice->parent_id)) {
                continue;
            }

            if ($invoice->recurring) {
                $recurred_invoices = Invoice::where('parent_id', $invoice->id)->accrued()->monthsOfYear($date)->get();

                foreach ($invoice->recurring->schedule() as $recurr) {
                    if ($recurred_invoices->count() > $recurr->getIndex()) {
                        continue;
                    }

                    if ($recurr->getStart()->format('Y') != Date::now()->format('Y')) {
                        continue;
                    }

                    $recurr_invoice = clone $invoice;

                    $recurr_invoice->parent_id = $invoice->id;
                    $recurr_invoice->created_at = $recurr->getStart()->format('Y-m-d');
                    $recurr_invoice->invoiced_at = $recurr->getStart()->format('Y-m-d');
                    $recurr_invoice->due_at = $recurr->getEnd()->format('Y-m-d');

                    $invoices->push($recurr_invoice);
                }
            }
        }
    }

    private function setAmount(&$graph, &$totals, &$incomes, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if ($item['table'] == 'invoice_payments') {
                $invoice = $item->invoice;

                $item->category_id = $invoice->category_id;
            }

            $date = Date::parse($item->$date_field)->format('F');

            if (!isset($incomes[$item->category_id])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if (($type == 'invoice') && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $incomes[$item->category_id][$date]['amount'] += $amount;
            $incomes[$item->category_id][$date]['currency_code'] = $item->currency_code;
            $incomes[$item->category_id][$date]['currency_rate'] = $item->currency_rate;

            $graph[Date::parse($item->$date_field)->format('F-Y')] += $amount;

            $totals[$date]['amount'] += $amount;
        }
    }
}
