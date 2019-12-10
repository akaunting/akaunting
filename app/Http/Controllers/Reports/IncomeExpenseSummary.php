<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Banking\Account;
use App\Models\Income\Customer;
use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\Revenue;
use App\Models\Expense\Bill;
use App\Models\Expense\BillPayment;
use App\Models\Expense\Payment;
use App\Models\Expense\Vendor;
use App\Models\Setting\Category;
use App\Utilities\Recurring;
use App\Traits\DateTime;
use Charts;
use Date;

class IncomeExpenseSummary extends Controller
{
    use DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dates = $totals = $compares = $profit_graph = $categories = [];

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

        $categories_filter = request('categories');

        $income_categories = Category::enabled()->type('income')->when($categories_filter, function ($query) use ($categories_filter) {
            return $query->whereIn('id', $categories_filter);
        })->orderBy('name')->pluck('name', 'id')->toArray();

        $expense_categories = Category::enabled()->type('expense')->when($categories_filter, function ($query) use ($categories_filter) {
            return $query->whereIn('id', $categories_filter);
        })->orderBy('name')->pluck('name', 'id')->toArray();

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            
            $dates[$j] = Date::parse($ym_string)->format('F');

            $profit_graph[Date::parse($ym_string)->format('F-Y')] = 0;

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

        $revenues = Revenue::monthsOfYear('paid_at')->account(request('accounts'))->customer(request('customers'))->isNotTransfer()->get();
        $payments = Payment::monthsOfYear('paid_at')->account(request('accounts'))->vendor(request('vendors'))->isNotTransfer()->get();

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::monthsOfYear('paid_at')->account(request('accounts'))->get();
                $this->setAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'paid_at');

                // Revenues
                $this->setAmount($profit_graph, $totals, $compares, $revenues, 'revenue', 'paid_at');

                // Bills
                $bills = BillPayment::monthsOfYear('paid_at')->account(request('accounts'))->get();
                $this->setAmount($profit_graph, $totals, $compares, $bills, 'bill', 'paid_at');

                // Payments
                $this->setAmount($profit_graph, $totals, $compares, $payments, 'payment', 'paid_at');
                break;
            case 'upcoming':
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('due_at')->customer(request('customers'))->get();
                Recurring::reflect($invoices, 'invoice', 'due_at', $status);
                $this->setAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'due_at');

                // Revenues
                Recurring::reflect($revenues, 'revenue', 'paid_at', $status);
                $this->setAmount($profit_graph, $totals, $compares, $revenues, 'revenue', 'paid_at');

                // Bills
                $bills = Bill::accrued()->monthsOfYear('due_at')->vendor(request('vendors'))->get();
                Recurring::reflect($bills, 'bill', 'billed_at', $status);
                $this->setAmount($profit_graph, $totals, $compares, $bills, 'bill', 'due_at');

                // Payments
                Recurring::reflect($payments, 'payment', 'paid_at', $status);
                $this->setAmount($profit_graph, $totals, $compares, $payments, 'payment', 'paid_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->customer(request('customers'))->get();
                Recurring::reflect($invoices, 'invoice', 'invoiced_at', $status);
                $this->setAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'invoiced_at');

                // Revenues
                Recurring::reflect($revenues, 'revenue', 'paid_at', $status);
                $this->setAmount($profit_graph, $totals, $compares, $revenues, 'revenue', 'paid_at');

                // Bills
                $bills = Bill::accrued()->monthsOfYear('billed_at')->vendor(request('vendors'))->get();
                Recurring::reflect($bills, 'bill', 'billed_at', $status);
                $this->setAmount($profit_graph, $totals, $compares, $bills, 'bill', 'billed_at');

                // Payments
                Recurring::reflect($payments, 'payment', 'paid_at', $status);
                $this->setAmount($profit_graph, $totals, $compares, $payments, 'payment', 'paid_at');
                break;
        }

        $statuses = collect([
            'all' => trans('general.all'),
            'paid' => trans('invoices.paid'),
            'upcoming' => trans('general.upcoming'),
        ]);

        $accounts = Account::enabled()->pluck('name', 'id')->toArray();
        $customers = Customer::enabled()->pluck('name', 'id')->toArray();
        $vendors = Vendor::enabled()->pluck('name', 'id')->toArray();
        $categories = Category::enabled()->type(['income', 'expense'])->pluck('name', 'id')->toArray();

        // Check if it's a print or normal request
        if (request('print')) {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line_print';
            $view_template = 'reports.income_expense_summary.print';
        } else {
            $chart_template = 'vendor.consoletvs.charts.chartjs.multi.line';
            $view_template = 'reports.income_expense_summary.index';
        }

        $print_url = $this->getPrintUrl(is_array($year) ? $year[0] : $year);

        // Profit chart
        $chart = Charts::multi('line', 'chartjs')
            ->dimensions(0, 300)
            ->colors(['#6da252'])
            ->dataset(trans_choice('general.profits', 1), $profit_graph)
            ->labels($dates)
            ->credits(false)
            ->view($chart_template);

        return view($view_template, compact(
            'chart',
            'dates',
            'income_categories',
            'expense_categories',
            'categories',
            'statuses',
            'accounts',
            'customers',
            'vendors',
            'compares',
            'totals',
            'print_url'
        ));
    }

    private function setAmount(&$graph, &$totals, &$compares, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if (($item->getTable() == 'bill_payments') || ($item->getTable() == 'invoice_payments')) {
                $type_item = $item->$type;

                $item->category_id = $type_item->category_id;
            }

            if ($item->getTable() == 'invoice_payments') {
                $invoice = $item->invoice;

                if ($customers = request('customers')) {
                    if (!in_array($invoice->customer_id, $customers)) {
                        continue;
                    }
                }

                $item->category_id = $invoice->category_id;
            }

            if ($item->getTable() == 'bill_payments') {
                $bill = $item->bill;

                if ($vendors = request('vendors')) {
                    if (!in_array($bill->vendor_id, $vendors)) {
                        continue;
                    }
                }

                $item->category_id = $bill->category_id;
            }

            if (($item->getTable() == 'invoices') || ($item->getTable() == 'bills')) {
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

            $group = (($type == 'invoice') || ($type == 'revenue')) ? 'income' : 'expense';

            if (!isset($compares[$group][$item->category_id]) || !isset($compares[$group][$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if ((($type == 'invoice') || ($type == 'bill')) && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $compares[$group][$item->category_id][$month]['amount'] += $amount;
            $compares[$group][$item->category_id][$month]['currency_code'] = $item->currency_code;
            $compares[$group][$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            if ($group == 'income') {
                $graph[$month_year] += $amount;

                $totals[$month]['amount'] += $amount;
            } else {
                $graph[$month_year] -= $amount;

                $totals[$month]['amount'] -= $amount;
            }
        }
    }

    private function getPrintUrl($year)
    {
        $print_url = 'reports/income-expense-summary?print=1'
            . '&status=' . request('status')
            . '&year='. request('year', $year);

        collect(request('accounts'))->each(function($item) use(&$print_url) {
            $print_url .= '&accounts[]=' . $item;
        });

        collect(request('customers'))->each(function($item) use(&$print_url) {
            $print_url .= '&customers[]=' . $item;
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
