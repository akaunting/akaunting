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
        $income_transactions = $this->applyFilters(
            model: Transaction::with('recurring')->income()->isNotTransfer(),
            args: ['date_field' => 'paid_at', 'model_type' => 'income'],
        );
        $expense_transactions = $this->applyFilters(
            model: Transaction::with('recurring')->expense()->isNotTransfer(),
            args: ['date_field' => 'paid_at', 'model_type' => 'expense'],
        );

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
                $invoices = $this->applyFilters(
                    model: Document::invoice()->with('recurring', 'transactions', 'items')->accrued(),
                    args: ['date_field' => 'issued_at', 'model_type' => 'invoice'],
                )->get();
                Recurring::reflect($invoices, 'issued_at');
                $this->setTotals($invoices, 'issued_at', false, 'income');

                // Incomes
                $incomes = $income_transactions->isNotDocument()->get();
                Recurring::reflect($incomes, 'paid_at');
                $this->setTotals($incomes, 'paid_at', false, 'income');

                // Bills
                $bills = $this->applyFilters(
                    model: Document::bill()->with('recurring', 'transactions', 'items')->accrued(),
                    args: ['date_field' => 'issued_at', 'model_type' => 'bill'],
                )->get();
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
