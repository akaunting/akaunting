<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Utilities\Recurring;

class ExpenseSummary extends Report
{
    public $default_name = 'reports.expense_summary';

    public $icon = 'shopping_cart';

    public $type = 'summary';

    public $chart = [
        'bar' => [
            'colors' => [
                '#fb7185',
            ],
        ],
        'donut' => [
            //
        ],
    ];

    public function setTables()
    {
        $this->tables = [
            'expense' => trans_choice('general.expenses', 2),
        ];
    }

    public function setData()
    {
        $transactions = $this->applyFilters(
            model: Transaction::with('recurring')->expense()->isNotTransfer(),
            args: ['date_field' => 'paid_at', 'model_type' => 'expense'],
        );

        switch ($this->getBasis()) {
            case 'cash':
                // Expenses
                $expenses = $transactions->get();
                $this->setTotals($expenses, 'paid_at', false, 'expense');

                break;
            default:
                // Bills
                $bills = $this->applyFilters(
                    model: Document::bill()->with('recurring', 'transactions', 'items')->accrued(),
                    args: ['date_field' => 'issued_at', 'model_type' => 'bill'],
                )->get();
                Recurring::reflect($bills, 'issued_at');
                $this->setTotals($bills, 'issued_at', false, 'expense');

                // Expenses
                $expenses = $transactions->isNotDocument()->get();
                Recurring::reflect($expenses, 'paid_at');
                $this->setTotals($expenses, 'paid_at', false, 'expense');

                break;
        }
    }
}
