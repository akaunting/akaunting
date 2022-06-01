<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Utilities\Recurring;

class IncomeExpenseSummary extends Report
{
    public $default_name = 'reports.income_expense_summary';

    public $icon = 'assessment';

    public $type = 'summary';

    public $chart = [
        'income' => [
            'bar' => [
                'colors' => [
                    '#8bb475',
                ],
            ],
            'donut' => [
                //
            ],
        ],
        'expense' => [
            'bar' => [
                'colors' => [
                    '#fb7185',
                ],
            ],
            'donut' => [
                //
            ],
        ],
    ];

    public function setTables()
    {
        $this->tables = [
            'income' => trans_choice('general.incomes', 1),
            'expense' => trans_choice('general.expenses', 2),
        ];
    }

    public function setData()
    {
        $income_transactions = $this->applyFilters(Transaction::with('recurring')->income()->isNotTransfer(), ['date_field' => 'paid_at']);
        $expense_transactions = $this->applyFilters(Transaction::with('recurring')->expense()->isNotTransfer(), ['date_field' => 'paid_at']);

        switch ($this->getBasis()) {
            case 'cash':
                // Incomes
                $incomes = $income_transactions->get();
                $this->setTotals($incomes, 'paid_at', false, 'income');

                // Expenses
                $expenses = $expense_transactions->get();
                $this->setTotals($expenses, 'paid_at', false, 'expense');

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Document::invoice()->with('recurring', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($invoices, 'issued_at');
                $this->setTotals($invoices, 'issued_at', false, 'income');

                // Incomes
                $incomes = $income_transactions->isNotDocument()->get();
                Recurring::reflect($incomes, 'paid_at');
                $this->setTotals($incomes, 'paid_at', false, 'income');

                // Bills
                $bills = $this->applyFilters(Document::bill()->with('recurring', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($bills, 'issued_at');
                $this->setTotals($bills, 'issued_at', false, 'expense');

                // Expenses
                $expenses = $expense_transactions->isNotDocument()->get();
                Recurring::reflect($expenses, 'paid_at');
                $this->setTotals($expenses, 'paid_at', false, 'expense');

                break;
        }
    }
}
