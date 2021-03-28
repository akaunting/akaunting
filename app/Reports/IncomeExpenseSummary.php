<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Utilities\Recurring;

class IncomeExpenseSummary extends Report
{
    public $default_name = 'reports.summary.income_expense';

    public $icon = 'fa fa-chart-pie';

    public function setData()
    {
        $income_transactions = $this->applyFilters(Transaction::with('recurring')->income()->isNotTransfer(), ['date_field' => 'paid_at']);
        $expense_transactions = $this->applyFilters(Transaction::with('recurring')->expense()->isNotTransfer(), ['date_field' => 'paid_at']);

        switch ($this->getSetting('basis')) {
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
                $invoices = $this->applyFilters(Document::invoice()->with('recurring', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($invoices, 'issued_at');
                $this->setTotals($invoices, 'issued_at', true);

                // Revenues
                $revenues = $income_transactions->isNotDocument()->get();
                Recurring::reflect($revenues, 'paid_at');
                $this->setTotals($revenues, 'paid_at', true);

                // Bills
                $bills = $this->applyFilters(Document::bill()->with('recurring', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($bills, 'issued_at');
                $this->setTotals($bills, 'issued_at', true);

                // Payments
                $payments = $expense_transactions->isNotDocument()->get();
                Recurring::reflect($payments, 'paid_at');
                $this->setTotals($payments, 'paid_at', true);

                break;
        }
    }
}
