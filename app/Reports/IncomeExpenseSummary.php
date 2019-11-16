<?php

namespace App\Reports;

use App\Abstracts\Reports\Report;
use App\Models\Banking\Transaction;
use App\Models\Expense\Bill;
use App\Models\Income\Invoice;
use App\Utilities\Recurring;

class IncomeExpenseSummary extends Report
{
    public $category = 'income-expense';

    public $icon = 'fa fa-chart-pie';

    public function getName()
    {
        return trans('reports.summary.income_expense');
    }

    public function getTotals()
    {
        $revenues = $this->applyFilters(Transaction::type('income')->isNotTransfer(), ['date_field' => 'paid_at'])->get();
        $payments = $this->applyFilters(Transaction::type('expense')->isNotTransfer(), ['date_field' => 'paid_at'])->get();

        switch ($this->report->basis) {
            case 'cash':
                // Revenues
                $this->setTotals($revenues, 'paid_at', true);

                // Payments
                $this->setTotals($payments, 'paid_at', true);

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoice', 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at', true);

                // Revenues
                Recurring::reflect($revenues, 'revenue', 'paid_at');
                $this->setTotals($revenues, 'paid_at', true);

                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'bill', 'billed_at');
                $this->setTotals($bills, 'billed_at', true);

                // Payments
                Recurring::reflect($payments, 'payment', 'paid_at');
                $this->setTotals($payments, 'paid_at', true);

                break;
        }
    }
}
