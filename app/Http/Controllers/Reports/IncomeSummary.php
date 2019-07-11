<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Banking\Account;
use App\Models\Income\Customer;
use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\Revenue;
use App\Models\Setting\Category;
use App\Utilities\Recurring;
use App\Traits\DateTime;
use Charts;
use Date;

class IncomeSummary extends Controller
{
    use DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dates = $totals = $incomes = $incomes_graph = $categories = [];

        $status = request('status');
        $year = request('year', Date::now()->year);
        
        // check and assign year start
        $financial_start = $this->getFinancialStart();

        if ($financial_start->month != 1) {
            // check if a specific year is requested
            if (!is_null(request('year'))) {
                $financial_start->year = $year;
            }

            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $categories = Category::enabled()->type('income')->orderBy('name')->pluck('name', 'id')->toArray();

        if ($categories_filter = request('categories')) {
            $cats = collect($categories)->filter(function ($value, $key) use ($categories_filter) {
                return in_array($key, $categories_filter);
            });
        } else {
            $cats = $categories;
        }

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            
            $dates[$j] = Date::parse($ym_string)->format('F');

            $incomes_graph[Date::parse($ym_string)->format('F-Y')] = 0;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            );

            foreach ($cats as $category_id => $category_name) {
                $incomes[$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1
                ];
            }
        }

        $revenues = Revenue::monthsOfYear('paid_at')->account(request('accounts'))->customer(request('customers'))->isNotTransfer()->get();

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::monthsOfYear('paid_at')->account(request('accounts'))->get();
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'paid_at');

                // Revenues
                $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
                break;
            case 'upcoming':
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('due_at')->customer(request('customers'))->get();
                Recurring::reflect($invoices, 'invoice', 'invoiced_at', $status);
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'due_at');

                // Revenues
                Recurring::reflect($revenues, 'revenue', 'paid_at', $status);
                $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->customer(request('customers'))->get();
                Recurring::reflect($invoices, 'invoice', 'invoiced_at', $status);
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'invoiced_at');

                // Revenues
                Recurring::reflect($revenues, 'revenue', 'paid_at', $status);
                $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
                break;
        }

        $statuses = collect([
            'all' => trans('general.all'),
            'paid' => trans('invoices.paid'),
            'upcoming' => trans('dashboard.receivables'),
        ]);

        $accounts = Account::enabled()->pluck('name', 'id')->toArray();
        $customers = Customer::enabled()->pluck('name', 'id')->toArray();

        // Check if it's a print or normal request
        if (request('print')) {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line_print';
            $view_template = 'reports.income_summary.print';
        } else {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line';
            $view_template = 'reports.income_summary.index';
        }

        $print_url = $this->getPrintUrl(is_array($year) ? $year[0] : $year);

        // Incomes chart
        $chart = Charts::multi('line', 'chartjs')
            ->dimensions(0, 300)
            ->colors(['#00c0ef'])
            ->dataset(trans_choice('general.incomes', 1), $incomes_graph)
            ->labels($dates)
            ->credits(false)
            ->view($chart_template);

        return view($view_template, compact(
            'chart',
            'dates',
            'categories',
            'statuses',
            'accounts',
            'customers',
            'incomes',
            'totals',
            'print_url'
        ));
    }

    private function setAmount(&$graph, &$totals, &$incomes, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if ($item->getTable() == 'invoice_payments') {
                $invoice = $item->invoice;

                if ($customers = request('customers')) {
                    if (!in_array($invoice->customer_id, $customers)) {
                        continue;
                    }
                }

                $item->category_id = $invoice->category_id;
            }

            if ($item->getTable() == 'invoices') {
                if ($accounts = request('accounts')) {
                    foreach ($item->payments as $payment) {
                        if (!in_array($payment->account_id, $accounts)) {
                            continue 2;
                        }
                    }
                }
            }

            $month = Date::parse($item->$date_field)->format('F');
            $month_year = Date::parse($item->$date_field)->format('F-Y');

            if (!isset($incomes[$item->category_id]) || !isset($incomes[$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if (($type == 'invoice') && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $incomes[$item->category_id][$month]['amount'] += $amount;
            $incomes[$item->category_id][$month]['currency_code'] = $item->currency_code;
            $incomes[$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            $graph[$month_year] += $amount;

            $totals[$month]['amount'] += $amount;
        }
    }

    private function getPrintUrl($year)
    {
        $print_url = 'reports/income-summary?print=1'
            . '&status=' . request('status')
            . '&year='. request('year', $year);

        collect(request('accounts'))->each(function($item) use(&$print_url) {
            $print_url .= '&accounts[]=' . $item;
        });

        collect(request('customers'))->each(function($item) use(&$print_url) {
            $print_url .= '&customers[]=' . $item;
        });

        collect(request('categories'))->each(function($item) use(&$print_url) {
            $print_url .= '&categories[]=' . $item;
        });

        return $print_url;
    }
}
