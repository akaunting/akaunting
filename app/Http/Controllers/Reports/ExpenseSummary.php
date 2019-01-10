<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Banking\Account;
use App\Models\Expense\Bill;
use App\Models\Expense\BillPayment;
use App\Models\Expense\Payment;
use App\Models\Expense\Vendor;
use App\Models\Setting\Category;
use App\Utilities\Recurring;
use App\Traits\DateTime;
use Charts;
use Date;

class ExpenseSummary extends Controller
{
    use DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dates = $totals = $expenses = $expenses_graph = $categories = [];

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

        $categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id')->toArray();

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

            $expenses_graph[Date::parse($ym_string)->format('F-Y')] = 0;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            );

            foreach ($cats as $category_id => $category_name) {
                $expenses[$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1
                );
            }
        }

        $payments = Payment::monthsOfYear('paid_at')->account(request('accounts'))->vendor(request('vendors'))->isNotTransfer()->get();

        switch ($status) {
            case 'paid':
                // Bills
                $bills = BillPayment::monthsOfYear('paid_at')->account(request('accounts'))->get();
                $this->setAmount($expenses_graph, $totals, $expenses, $bills, 'bill', 'paid_at');

                // Payments
                $this->setAmount($expenses_graph, $totals, $expenses, $payments, 'payment', 'paid_at');
                break;
            case 'upcoming':
                // Bills
                $bills = Bill::accrued()->monthsOfYear('due_at')->vendor(request('vendors'))->get();
                Recurring::reflect($bills, 'bill', 'billed_at', $status);
                $this->setAmount($expenses_graph, $totals, $expenses, $bills, 'bill', 'due_at');

                // Payments
                Recurring::reflect($payments, 'payment', 'paid_at', $status);
                $this->setAmount($expenses_graph, $totals, $expenses, $payments, 'payment', 'paid_at');
                break;
            default:
                // Bills
                $bills = Bill::accrued()->monthsOfYear('billed_at')->vendor(request('vendors'))->get();
                Recurring::reflect($bills, 'bill', 'billed_at', $status);
                $this->setAmount($expenses_graph, $totals, $expenses, $bills, 'bill', 'billed_at');

                // Payments
                Recurring::reflect($payments, 'payment', 'paid_at', $status);
                $this->setAmount($expenses_graph, $totals, $expenses, $payments, 'payment', 'paid_at');
                break;
        }

        $statuses = collect([
            'all' => trans('general.all'),
            'paid' => trans('invoices.paid'),
            'upcoming' => trans('dashboard.payables'),
        ]);

        $accounts = Account::enabled()->pluck('name', 'id')->toArray();
        $vendors = Vendor::enabled()->pluck('name', 'id')->toArray();

        // Check if it's a print or normal request
        if (request('print')) {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line_print';
            $view_template = 'reports.expense_summary.print';
        } else {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line';
            $view_template = 'reports.expense_summary.index';
        }

        $print_url = $this->getPrintUrl(is_array($year) ? $year[0] : $year);

        // Expenses chart
        $chart = Charts::multi('line', 'chartjs')
            ->dimensions(0, 300)
            ->colors(['#F56954'])
            ->dataset(trans_choice('general.expenses', 1), $expenses_graph)
            ->labels($dates)
            ->credits(false)
            ->view($chart_template);

        return view($view_template, compact(
            'chart',
            'dates',
            'categories',
            'statuses',
            'accounts',
            'vendors',
            'expenses',
            'totals',
            'print_url'
        ));
    }

    private function setAmount(&$graph, &$totals, &$expenses, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if ($item->getTable() == 'bill_payments') {
                $bill = $item->bill;

                if ($vendors = request('vendors')) {
                    if (!in_array($bill->vendor_id, $vendors)) {
                        continue;
                    }
                }

                $item->category_id = $bill->category_id;
            }

            if ($item->getTable() == 'bills') {
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

            if (!isset($expenses[$item->category_id]) || !isset($expenses[$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if (($type == 'bill') && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $expenses[$item->category_id][$month]['amount'] += $amount;
            $expenses[$item->category_id][$month]['currency_code'] = $item->currency_code;
            $expenses[$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            $graph[$month_year] += $amount;

            $totals[$month]['amount'] += $amount;
        }
    }

    private function getPrintUrl($year)
    {
        $print_url = 'reports/expense-summary?print=1'
            . '&status=' . request('status')
            . '&year='. request('year', $year);

        collect(request('accounts'))->each(function($item) use(&$print_url) {
            $print_url .= '&accounts[]=' . $item;
        });

        collect(request('vendors'))->each(function($item) use(&$print_url) {
            $print_url .= '&vendors[]=' . $item;
        });

        collect(request('categories'))->each(function($item) use(&$print_url) {
            $print_url .= '&categories[]=' . $item;
        });

        return $print_url;
    }
}
