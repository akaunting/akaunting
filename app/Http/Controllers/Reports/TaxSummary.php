<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Expense\Bill;
use App\Models\Expense\BillPayment;
use App\Models\Expense\BillTotal;
use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceTotal;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use Date;

class TaxSummary extends Controller
{
    use Currencies;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $dates = $incomes = $expenses = $totals = [];

        $status = request('status');

        $t = Tax::enabled()->where('rate', '<>', '0')->pluck('name')->toArray();

        $taxes = array_combine($t, $t);

        // Get year
        $year = request('year');
        if (empty($year)) {
            $year = Date::now()->year;
        }

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $dates[$j] = Date::parse($year . '-' . $j)->format('M');

            foreach ($taxes as $tax_name) {
                $incomes[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1,
                ];

                $expenses[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1,
                ];

                $totals[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => setting('general.default_currency'),
                    'currency_rate' => 1,
                ];
            }
        }

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::with(['invoice', 'invoice.totals'])->monthsOfYear('paid_at')->get();
                $this->setAmount($incomes, $totals, $invoices, 'invoice', 'paid_at');
                // Bills
                $bills = BillPayment::with(['bill', 'bill.totals'])->monthsOfYear('paid_at')->get();
                $this->setAmount($expenses, $totals, $bills, 'bill', 'paid_at');
                break;
            case 'upcoming':
                // Invoices
                $invoices = Invoice::with(['totals'])->accrued()->monthsOfYear('due_at')->get();
                $this->setAmount($incomes, $totals, $invoices, 'invoice', 'due_at');
                // Bills
                $bills = Bill::with(['totals'])->accrued()->monthsOfYear('due_at')->get();
                $this->setAmount($expenses, $totals, $bills, 'bill', 'due_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::with(['totals'])->accrued()->monthsOfYear('invoiced_at')->get();
                $this->setAmount($incomes, $totals, $invoices, 'invoice', 'invoiced_at');
                // Bills
                $bills = Bill::with(['totals'])->accrued()->monthsOfYear('billed_at')->get();
                $this->setAmount($expenses, $totals, $bills, 'bill', 'billed_at');
                break;
        }

        // Check if it's a print or normal request
        if (request('print')) {
            $view_template = 'reports.tax_summary.print';
        } else {
            $view_template = 'reports.tax_summary.index';
        }

        return view($view_template, compact('dates', 'taxes', 'incomes', 'expenses', 'totals'));
    }

    private function setAmount(&$items, &$totals, $rows, $type, $date_field)
    {
        foreach ($rows as $row) {
            $date = Date::parse($row->$date_field)->format('M');

            if ($date_field == 'paid_at') {
                $row_totals = $row->$type->totals;
            } else {
                $row_totals = $row->totals;
            }

            foreach ($row_totals as $row_total) {
                if ($row_total->code != 'tax') {
                    continue;
                }

                if (!isset($items[$row_total->name])) {
                    continue;
                }

                $amount = $this->convert($row_total->amount, $row->currency_code, $row->currency_rate);

                $items[$row_total->name][$date]['amount'] += $amount;

                if ($type == 'invoice') {
                    $totals[$row_total->name][$date]['amount'] += $amount;
                } else {
                    $totals[$row_total->name][$date]['amount'] -= $amount;
                }
            }
        }
    }
}
