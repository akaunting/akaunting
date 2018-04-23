<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\Revenue;
use App\Models\Expense\Bill;
use App\Models\Expense\BillPayment;
use App\Models\Expense\Payment;
use App\Models\Setting\Category;
use Charts;
use Date;

class IncomeExpenseSummary extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dates = $totals = $compares = $profit_graph = $categories = [];

        $status = request('status');

        $income_categories = Category::enabled()->type('income')->pluck('name', 'id')->toArray();

        $expense_categories = Category::enabled()->type('expense')->pluck('name', 'id')->toArray();

        // Get year
        $year = request('year');
        if (empty($year)) {
            $year = Date::now()->year;
        }

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $dates[$j] = Date::parse($year . '-' . $j)->format('F');

            $profit_graph[Date::parse($year . '-' . $j)->format('F-Y')] = 0;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            );

            foreach ($income_categories as $category_id => $category_name) {
                $compares['income'][$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1
                );
            }

            foreach ($expense_categories as $category_id => $category_name) {
                $compares['expense'][$category_id][$dates[$j]] = array(
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
                $this->setAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'paid_at');
                break;
            case 'upcoming':
                $invoices = Invoice::accrued()->monthsOfYear('due_at')->get();
                $this->setAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'due_at');
                break;
            default:
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'invoiced_at');
                break;
        }

        // Revenues
        if ($status != 'upcoming') {
            $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
            $this->setAmount($profit_graph, $totals, $compares, $revenues, 'revenue', 'paid_at');
        }

        // Bills
        switch ($status) {
            case 'paid':
                $bills = BillPayment::monthsOfYear('paid_at')->get();
                $this->setAmount($profit_graph, $totals, $compares, $bills, 'bill', 'paid_at');
                break;
            case 'upcoming':
                $bills = Bill::accrued()->monthsOfYear('due_at')->get();
                $this->setAmount($profit_graph, $totals, $compares, $bills, 'bill', 'due_at');
                break;
            default:
                $bills = Bill::accrued()->monthsOfYear('billed_at')->get();
                $this->setAmount($profit_graph, $totals, $compares, $bills, 'bill', 'billed_at');
                break;
        }
        
        // Payments
        if ($status != 'upcoming') {
            $payments = Payment::monthsOfYear('paid_at')->isNotTransfer()->get();
            $this->setAmount($profit_graph, $totals, $compares, $payments, 'payment', 'paid_at');
        }

        // Check if it's a print or normal request
        if (request('print')) {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line_print';
            $view_template = 'reports.income_expense_summary.print';
        } else {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line';
            $view_template = 'reports.income_expense_summary.index';
        }

        // Profit chart
        $chart = Charts::multi('line', 'chartjs')
            ->dimensions(0, 300)
            ->colors(['#6da252'])
            ->dataset(trans_choice('general.profits', 1), $profit_graph)
            ->labels($dates)
            ->credits(false)
            ->view($chart_template);

        return view($view_template, compact('chart', 'dates', 'income_categories', 'expense_categories', 'compares', 'totals'));
    }

    private function setAmount(&$graph, &$totals, &$compares, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            $date = Date::parse($item->$date_field)->format('F');

            $group = (($type == 'invoice') || ($type == 'revenue')) ? 'income' : 'expense';

            if (!isset($compares[$group][$item->category_id])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if ((($type == 'invoice') || ($type == 'bill')) && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $compares[$group][$item->category_id][$date]['amount'] += $amount;
            $compares[$group][$item->category_id][$date]['currency_code'] = $item->currency_code;
            $compares[$group][$item->category_id][$date]['currency_rate'] = $item->currency_rate;

            if ($group == 'income') {
                $graph[Date::parse($item->$date_field)->format('F-Y')] += $amount;

                $totals[$date]['amount'] += $amount;
            } else {
                $graph[Date::parse($item->$date_field)->format('F-Y')] -= $amount;

                $totals[$date]['amount'] -= $amount;
            }
        }
    }
}
