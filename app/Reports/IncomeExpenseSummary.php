<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Utilities\Recurring;

class IncomeExpenseSummary extends Report
{
    public $icon = 'fa fa-chart-pie';

    public function getDefaultName()
    {
        return trans('reports.summary.income_expense');
    }

    public function getCategory()
    {
        return trans('reports.income_expense');
    }

    public function getTotals()
    {
        $income_transactions = $this->applyFilters(Transaction::type('income')->isNotTransfer(), ['date_field' => 'paid_at'])->get();
        $expense_transactions = $this->applyFilters(Transaction::type('expense')->isNotTransfer(), ['date_field' => 'paid_at'])->get();

        switch ($this->report->basis) {
            case 'cash':
                // Income Transactions
                $this->setTotals($income_transactions, 'paid_at', true);

                // Expense Transactions
                $this->setTotals($expense_transactions, 'paid_at', true);

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoice', 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at', true);

                // Income Transactions
                Recurring::reflect($income_transactions, 'transaction', 'paid_at');
                $this->setTotals($income_transactions, 'paid_at', true);

                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'bill', 'billed_at');
                $this->setTotals($bills, 'billed_at', true);

                // Expense Transactions
                Recurring::reflect($expense_transactions, 'transaction', 'paid_at');
                $this->setTotals($expense_transactions, 'paid_at', true);

                break;
        }
    }
}
