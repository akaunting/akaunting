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
use App\Traits\DateTime;
use Charts;
use Date;

class ProfitLoss extends Controller
{
    use DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dates = $totals = $compares = $categories = [];

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
            $financial_start->subYear()->subQuarter();
        }

        $income_categories = Category::enabled()->type('income')->orderBy('name')->pluck('name', 'id')->toArray();

        $expense_categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id')->toArray();

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addQuarter()->format('Y-m') : $year . '-' . $j;
            
            $dates[$j] = Date::parse($ym_string)->quarter;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            );

            foreach ($income_categories as $category_id => $category_name) {
                $compares['income'][$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1
                ];
            }

            foreach ($expense_categories as $category_id => $category_name) {
                $compares['expense'][$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1
                ];
            }

            $j += 2;
        }

        $totals['total'] = [
            'amount' => 0,
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => 1
        ];

        foreach ($dates as $date) {
            $gross['income'][$date] = 0;
            $gross['expense'][$date] = 0;
        }

        $gross['income']['total'] = 0;
        $gross['expense']['total'] = 0;

        foreach ($income_categories as $category_id => $category_name) {
            $compares['income'][$category_id]['total'] = [
                'category_id' => $category_id,
                'name' => trans_choice('general.totals', 1),
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            ];
        }

        foreach ($expense_categories as $category_id => $category_name) {
            $compares['expense'][$category_id]['total'] = [
                'category_id' => $category_id,
                'name' => trans_choice('general.totals', 1),
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            ];
        }

        // Invoices
        switch ($status) {
            case 'paid':
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                $this->setAmount($totals, $compares, $invoices, 'invoice', 'paid_at');
                break;
            case 'upcoming':
                $invoices = Invoice::accrued()->monthsOfYear('due_at')->get();
                $this->setAmount($totals, $compares, $invoices, 'invoice', 'due_at');
                break;
            default:
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setAmount($totals, $compares, $invoices, 'invoice', 'invoiced_at');
                break;
        }

        // Revenues
        if ($status != 'upcoming') {
            $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
            $this->setAmount($totals, $compares, $revenues, 'revenue', 'paid_at');
        }

        // Bills
        switch ($status) {
            case 'paid':
                $bills = BillPayment::monthsOfYear('paid_at')->get();
                $this->setAmount($totals, $compares, $bills, 'bill', 'paid_at');
                break;
            case 'upcoming':
                $bills = Bill::accrued()->monthsOfYear('due_at')->get();
                $this->setAmount($totals, $compares, $bills, 'bill', 'due_at');
                break;
            default:
                $bills = Bill::accrued()->monthsOfYear('billed_at')->get();
                $this->setAmount($totals, $compares, $bills, 'bill', 'billed_at');
                break;
        }
        
        // Payments
        if ($status != 'upcoming') {
            $payments = Payment::monthsOfYear('paid_at')->isNotTransfer()->get();
            $this->setAmount($totals, $compares, $payments, 'payment', 'paid_at');
        }

        $statuses = collect([
            'all' => trans('general.all'),
            'paid' => trans('invoices.paid'),
            'upcoming' => trans('general.upcoming'),
        ]);

        // Check if it's a print or normal request
        if (request('print')) {
            $view_template = 'reports.profit_loss.print';
        } else {
            $view_template = 'reports.profit_loss.index';
        }

        return view($view_template, compact('dates', 'income_categories', 'expense_categories', 'compares', 'totals', 'gross', 'statuses'));
    }

    private function setAmount(&$totals, &$compares, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if (($item->getTable() == 'bill_payments') || ($item->getTable() == 'invoice_payments')) {
                $type_item = $item->$type;

                $item->category_id = $type_item->category_id;
            }

            $date = Date::parse($item->$date_field)->quarter;

            $group = (($type == 'invoice') || ($type == 'revenue')) ? 'income' : 'expense';

            if (!isset($compares[$group][$item->category_id])) {
                continue;
            }

            $amount = $item->getConvertedAmount(false, false);

            // Forecasting
            if ((($type == 'invoice') || ($type == 'bill')) && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $compares[$group][$item->category_id][$date]['amount'] += $amount;
            $compares[$group][$item->category_id][$date]['currency_code'] = $item->currency_code;
            $compares[$group][$item->category_id][$date]['currency_rate'] = $item->currency_rate;
            $compares[$group][$item->category_id]['total']['amount'] += $amount;

            if ($group == 'income') {
                $totals[$date]['amount'] += $amount;
                $totals['total']['amount'] += $amount;
            } else {
                $totals[$date]['amount'] -= $amount;
                $totals['total']['amount'] -= $amount;
            }
        }
    }
}
