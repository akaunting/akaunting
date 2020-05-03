<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Utilities\Recurring;

class IncomeExpenseSummary extends Report
{
    public $default_name = 'reports.summary.income_expense';

    public $icon = 'fa fa-chart-pie';

    public function setData()
    {
        $income_transactions = $this->applyFilters(Transaction::income()->isNotTransfer(), ['date_field' => 'paid_at']);
        $expense_transactions = $this->applyFilters(Transaction::expense()->isNotTransfer(), ['date_field' => 'paid_at']);

        switch ($this->model->settings->basis) {
            case 'cash':
                // Revenues
                $revenues = $income_transactions->get();
                $this->setTotals($revenues, 'paid_at', true);

                // Payments
                $payments = $expense_transactions->get();
                $this->setTotals($payments, 'paid_at', true);

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at', true);

                // Revenues
                $revenues = $income_transactions->isNotDocument()->get();
                Recurring::reflect($revenues, 'paid_at');
                $this->setTotals($revenues, 'paid_at', true);

                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'bill', 'billed_at');
                $this->setTotals($bills, 'billed_at', true);

                // Payments
                $payments = $expense_transactions->isNotDocument()->get();
                Recurring::reflect($payments, 'paid_at');
                $this->setTotals($payments, 'paid_at', true);

                break;
        }
    }
}
